tabs = [
    { active: true, state:"in"}, 
    { active: false, state:"out"}
]
  
function toogle(index=0){
    console.log(index);
    var elements = $(".nav-header");
    elements.each(function() {
        let id = $(this).attr('id');
        console.log(id);
    });
}