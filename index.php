<?php
	/* FREE OPEN-SOURCE PHP SURVEY SCRIPT by PAUL RAETCHI // paul@mokalife.ro // 2014-02-24 */
	session_start();

	$default = 'formular-inscriere.json';
	
	$base = substr($_SERVER['PHP_SELF'],0,-10);		

	if(isset($_GET['path']))
		$path = explode('/', $_GET['path']);
	else
		$path = array();

	if(isset($path[0]))
		$form_file = $path[0] . '.json';
	else
		$form_file = $default;

	if(file_exists($form_file)) {
		if(count($_POST)) {
			if(isset($path[0]))
				$survey_file = $path[0] . '-' . session_id() . '.json';
			else
				$survey_file = 'default -' . session_id() . '.json';
			if(file_exists($survey_file))
				die('Multumim, ' . ucfirst($_POST['nume']) . '! Ai completat deja acest formular!');
			$content = '{"timestamp":"' . date('Y-m-d H:i:s') . '", "content":' . json_encode($_POST) . '}';
			file_put_contents($survey_file, $content);
			die('Multumim, ' . ucfirst($_POST['nume']) . '!');
		} else
			$form = json_decode(file_get_contents($form_file));
	} else
		die(':(');
?>
<script>
function expand(e) {
	if(e.style.position == 'absolute') {
		e.style.position = 'inherit';
		e.style.width = '432px';
		e.style.margin = '0px';
		e.style.boxShadow = 'none';
	} else {
		e.style.position = 'absolute';
		e.style.width = '864px';
		e.style.boxShadow = '0px 0px 30px #000';
		e.style.margin = '-100px -216px';
	}
}
</script>
<form method="post" id="chestionar" style="font-family:Segoe UI;width:600px;background:#f4f4f4;border:4px solid #fff;border-radius:5px;padding:20px;box-shadow:0px 3px 10px rgba(0,0,0,.3), 0px 0px 10px #fff inset;margin:40px auto;">
<?php
	foreach($form as $title => $content)
		echo '<h2 style="margin:0px;text-align:center;">' . $title . '</h2>';
	
	echo '<ol>';

	$question_id = 0;
	$element = 0;

	foreach($content as $question => $answers)
		{
			$question_id++;
			$answer_id = 0;
			$oldtype = '';

			echo '<hr style="border:0px;border-top:1px solid #ddd;width:100%;margin:20px 0px 20px -20px">';
			echo '<li>' . $question . '</li>';
			echo '<input type="hidden" value="' . $question . '" name="q' . $question_id . '" />';
			echo '<ul>';

			foreach($answers as $answer => $type)
				{
					if(!(($oldtype == $type) && ($type == 'radio')))
						$answer_id++;
					$qid = 'q' . $question_id . 'a' . $answer_id;
					$oldtype = $type;
					$element++;

					echo '<li style="display:block">';
					switch ($type)
						{
							case 'check':
								echo '<label for="e' . $element . '">
									<input type="checkbox" id = "e' . $element . '" name="' . $qid . '" value="' . $answer . '" /> ' . $answer . '</label>';
								break;
							case 'radio':
								echo '<label for="e' . $element . '">
									<input type="radio" id = "e' . $element . '" name="' . $qid . '" value="' . $answer . '" /> ' . $answer . '</label>';
								break;
							case 'text':
								echo '<label for="e' . $element . '">' . $answer . '
									<input type="text" style="width:440px;" id = "e' . $element . '" name="' . $qid . '"></label>';
								break;
								break;
							case 'bigtext':
								echo '<label for="e' . $element . '">' . $answer . '
									<textarea style="width:440px;height:110px;" id = "e' . $element . '" name="' . $qid . '"></textarea></label>';
								break;
							case 'biggertext':
								echo '<label for="e' . $element . '">' . $answer . '
									<textarea style="width:440px;height:220px;" id = "e' . $element . '" name="' . $qid . '"></textarea></label>';
								break;
							case 'image':
								echo '<img src="' . $base . '/' . $answer . '" style="transition: all 500ms;cursor:pointer;width:432px;background:#fff;padding:3px;border:1px solid #888" onclick="expand(this);">';
								break;
							case 'note':
								echo '<span style="float:right;font-style:italic;color:#aaa">' . $answer . '</span>';
								break;
							case 'slider':
								echo '<label for="e' . $element . '">' . $answer . '
									<input type="range" min="-10" max="10" id = "e' . $element . '" name="' . $qid . '">';
								break;
						}
				}

			echo '</ul>';
		}
?>
<hr style="border:0px;border-top:1px solid #ddd;width:100%;margin:20px 0px 20px -20px">
<input type="text" id="nume" name="nume" placeholder="Nume" size="30" required />
<input type="email" id="email" name="email" placeholder="E-mail" size="35" required />
<input type="submit" id="submit" value="Trimite" />
</form>