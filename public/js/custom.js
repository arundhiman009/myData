/* Function being used in Social Share User side */
function myFunction() {
    var copyText = document.getElementById("ref_code");
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    document.execCommand("copy");
    $("#ref_btn").attr("title", "Copied!").tooltip("_fixTitle").tooltip("show").attr("title", "Copy to clipboard").tooltip("_fixTitle");
}

function outFunc() {
    var btn = document.getElementById("ref_btn");
    btn.title = "Copy link";
}

$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})


function SwalAlert(msg){
    if(!msg){
        msg = {title : "success", text  : "success", icon : "success"};
    }
    Swal.fire({
        title: msg.title,
        text: msg.text,
        icon: msg.icon
    })
}

function SwalProgress(){
    Swal.fire({
        title:'Please wait...',
        allowOutsideClick :false,
        didOpen: () => {
            Swal.showLoading()
        }
    })
}

function SwalHideProgress(){
    Swal.close();
}
