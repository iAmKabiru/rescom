<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	if (isset($_POST['compileform'])) {
		$content = <<<_DOC
		<p>
		Enter the student's result data into the fields below and click on continue when done.
	</p>
	<p>
		<strong>Please before continuing, endeavour to double check the student's result data to avoid omissions and/or mistakes.</strong>
	</p>
	<table class="table table-striped table-bordered table-hover" id="form-table">
	<thead>
		<th scope="col">Subject / Max. Score</th>
		<th scope="col">First Test</th>
		<th scope="col">Second Test</th>
		<th scope="col">Quiz</th>
		<th scope="col">Project</th>	
		<th scope="col">Exam</th>
		<th scope="col">Total</th>
		<th scope="col">Annual Average</th>
		<th scope="col">Letter Grade</th>
		<th scope="col">Interpretation of Grades</th>
	</thead>
	<tbody>
		<form action="" method="post" class="form-horizontal" id="compile-form1">
		<tr>
			<td>Basic Science</td>
			<td>
				<input type="text"  name="basic-firsttest" />
			</td>
			<td>
				<input type="text"  name="basic-secondtest" />
			</td>
			<td>
				<input type="text"  name="basic-quiz" />
			</td>
			<td>
				<input type="text"  name="basic-project" />
			</td>
			<td>
				<input type="text"  name="basic-exam" />
			</td>
			<td>
				<input type="text"  name="basic-total" class="total" />
			</td>
			<td>
				<input type="text"  name="basic-average" />
			</td>
			<td>
				<input type="text"  name="basic-grade" />
			</td>
			<td>
				<input type="text"  name="basic-interprete" />
			</td>
		</tr>
		<tr>
			<td>Christian Religious Studies</td>
			<td>
				<input type="text"  name="basic-firsttest" />
			</td>
			<td>
				<input type="text"  name="basic-secondtest" />
			</td>
			<td>
				<input type="text"  name="basic-quiz" />
			</td>
			<td>
				<input type="text"  name="basic-project" />
			</td>
			<td>
				<input type="text"  name="basic-exam" />
			</td>
			<td>
				<input type="text"  name="basic-total" class="total" />
			</td>
			<td>
				<input type="text"  name="basic-average" />
			</td>
			<td>
				<input type="text"  name="basic-grade" />
			</td>
			<td>
				<input type="text"  name="basic-interprete" />
			</td>
		</tr>
		</form>
	</tbody>
	</table>
_DOC;
		echo $content;
	}

}