<script>
var validNumber;
var lastValid=[];
function sizeValidate(row_index)
{
     validNumber = new RegExp(/^\d{0,5}(\.\d{0,4})?$/);
     lastValid[row_index] = $("#size"+row_index).val();
}
function sizeTextChangeValidation(i,elem)
{
    if (validNumber.test($('#'+elem.id).val())) {
    lastValid[i] = $('#'+elem.id).val();
    } else {
        $('#'+elem.id).val(lastValid[i]);
    }
}
</script>