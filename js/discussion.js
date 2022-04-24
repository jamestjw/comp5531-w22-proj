/*
James Juan Whei Tan - 40161156
Christopher Almeida Neves - 27521979
*/
$(".replyMessage")
.click(function(e){
    e.preventDefault();
    $(e.target).parent().find("form").toggle();


});

document
    .querySelector("#addPollOption")
    .addEventListener("click", function(e) {
        let optionCountNode = document.querySelector("#option_count");
        var numOptions = parseInt(optionCountNode.value);
        numOptions++;

        optionCountNode.setAttribute("value", numOptions);

        const newOption = document.createElement("input");
        newOption.setAttribute("id", "option_"+numOptions);
        newOption.setAttribute("name", "option_"+numOptions);
        newOption.setAttribute("type", "text");
        newOption.setAttribute("placeholder", "Poll option "+numOptions);

        document.getElementById("pollForm").appendChild(newOption);
});

document
    .querySelector("#displayAddPoll")
    .addEventListener("click", function(e) {
        document.getElementById("pollForm").style.display = "initial";
        e.target.style.display = "none";
});

if (document.querySelector('#pollVote')) {
    document.querySelector('#pollVote').addEventListener('submit', (e) => {
        e.preventDefault();
        const data = Object.fromEntries(new FormData(e.target).entries());

        // TODO: How to handle failure?
        var request = new XMLHttpRequest();
        request.open("POST", "poll.php");
        request.send(new FormData(e.target));
        location.reload();
    });
}
