
// Get the <span> element that closes the modal
var closeModal = document.getElementsByClassName("close")[0];



// // When the user clicks anywhere outside of the modal, close it
// window.onclick = function(event) {
//     if (event.target == modal) {
//         modal.style.display = "none";
//     }
// }

function closePortfolioModal(modalName) {
	
	var modal = document.getElementById(modalName);
	modal.style.display = "none";

}

function openPortfolioModal(modalName, modalBtnName) {

	var modal = document.getElementById(modalName);

	var modalBtn = document.getElementById(modalBtnName);

	modal.style.display	= "block";

	if (modal.style.display	= "block") {
		modal.style.display = "none";
	}

	if (modal.style.display = "none") {
		modal.style.display = "block";
	}
}


// function openModal("myModal","openPortfolio1");
