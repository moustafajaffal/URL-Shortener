function copyToClipboard() {
    var copyText = document.getElementById("result");
    var result = copyText.textContent;

    navigator.clipboard.writeText(result);
}