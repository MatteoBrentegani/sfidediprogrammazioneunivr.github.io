function hide(radio, element) {
	document.getElementById(element).hidden = radio.checked;
}

function show(radio, element) {
	document.getElementById(element).hidden = !radio.checked;
}

const languages = [
	"Assembly",
	"Bash",
	"Basic",
	"C",
	"C++",
	"C#",
	"D",
	"Go",
	"Haskell",
	"Java",
	"JavaScript",
	"Kotlin",
	"Lisp",
	"Objective-C",
	"OCAML",
	"Pascal",
	"Perl",
	"PHP",
	"Python",
	"Ruby",
	"Rust",
	"Swift",
]

const experience_levels = [
	"0-100",
	"100-1.000",
	"1.000-10.000",
	"10.000+",
]

var selected_languages = new Set();

function create_select_experience(experience) {
	var select = document.createElement('select');
	select.className = "form-control";
	for (var i = 0; i < experience_levels.length; i++) {
		var option = document.createElement('option');
		if (experience == experience_levels[i])
			option.selected = true;
		option.value = option.innerHTML = experience_levels[i];
		select.add(option);
	}
	return select;
}

function update_language_select() {
	var language_name_select = document.getElementById("language-name-select");
	language_name_select.innerHTML = null;
	for (var i = 0; i < languages.length; i++) {
		if (selected_languages.has(languages[i]))
			continue;
		var option = document.createElement("option");
		option.value = option.innerHTML = languages[i];
		language_name_select.add(option);
	}
}

window.onload = () => {
	update_language_select();
	var corsostudi = document.getElementById('corsostudi');
	corsostudi.onchange = () => {
		var anno3 = document.getElementById('anno3');
		anno3.hidden = corsostudi.value.includes('magistrale');
	}

}

function add_language() {
	var language_name_select = document.getElementById("language-name-select");
	var name = language_name_select.value;
	selected_languages.add(name);
	var experience =document.getElementById("language-experience-select").value;
	var language_table = document.getElementById("language-table");
	var row = language_table.insertRow(language_table.rows.length - 1);
	var delete_button = document.createElement("button")
	delete_button.onclick = () => { 
		language_table.deleteRow(row.rowIndex);
		selected_languages.delete(name);
		update_language_select();
	}
	delete_button.innerText = "Rimuovi";
	delete_button.className = "btn btn-default";
	var select = create_select_experience(experience);
	select.name = "languages[" + name.toLowerCase() + "]";
	var cell = row.insertCell(0);
	cell.style = "vertical-align: middle";
	cell.innerText = name;
	row.insertCell(1).appendChild(select);
	row.insertCell(2).appendChild(delete_button);
	update_language_select();
}

