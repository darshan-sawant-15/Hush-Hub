function search() {
    var searchTerm = document.getElementsByName("searchTerm")[0].value;
    var xmlhttpSearch = new XMLHttpRequest();
    xmlhttpSearch.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("usersContainer").innerHTML = this.responseText;
        }
    }
    xmlhttpSearch.open("GET", "../function-files/user-functions.php?action=giveSearchedUsers&searchTerm=" + searchTerm, true);
    xmlhttpSearch.send();
}