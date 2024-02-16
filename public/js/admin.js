const buttons = document.querySelectorAll("button");
buttons.forEach(function(buttonItem) {
	buttonItem.addEventListener("click", function(e) {
		if(e.target.hasAttribute('data-iddelete')) {
			if(confirm("Etes-vous sur de vouloir supprimer cet article ?"))
			{
				location.href = location.href + "/supprimer?id="+e.target.getAttribute('data-iddelete') ;
			}
		}
	});
});
const toggleRows = document.querySelectorAll(".toggle-row");
toggleRows.forEach(function(toggleRowItem) {
	toggleRowItem.addEventListener("click", function(e) {
		if(e.target.hasAttribute('data-idtogglerow')) {
			let element = document.getElementById("id_" + e.target.getAttribute('data-idtogglerow')) ;
			if (element.classList.contains("is-expanded"))
				element.classList.remove("is-expanded");
			else
				element.classList.add("is-expanded");
		}
	});
});