const all = document.getElementById('all');


all.addEventListener('click', toggle);

function toggle(){
    const isChecked = all.checked;
    Array.from(document.getElementsByClassName('chk_boxes1')).forEach(element =>{
        element.checked = isChecked; 
    });
}


Array.from(document.querySelectorAll('input:not(#all)')).forEach(element =>{
    element.addEventListener('click', uncheckAll);
});

function uncheckAll(){
    all.checked = false
}
