<button type="button" class="btn btn-info" data-toggle="modal" id="btn_form" data-target="#exampleModal"> <i
        class="lar la-file-excel"></i> Import Excel</button>
<div class="modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Choose excel file to import</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Recipient:</label>
                        <input type="file" class="file" name="file" id="file">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary " id="import">Save</button>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#btn_form').click(function() {
            $(".modal").animate({
                height: 'toggle',
            });
        })
        $("#import").click(function() {
            var formData = new FormData();
            formData.append('file', $('#file').get(0).files[0]);
            var data = $('.file').val();
            if (data == '') {
                new Noty({
                    type: "error",
                    text: "Please choose upload file."
                }).show();
            } else {
                $.ajax({
                    type: 'POST',
                    url: "{{ URL('importFile') }}",
                    data: formData,
                    dataType: 'json',
                    mimeType: 'multipart/form-data',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(result) {
                        console.log(result);
                        if (result.status == true) {
                            $('.modal').hide();
                            $('#crudTable_wrapper').find('table').DataTable().ajax.reload();
                            $('.file').val('');
                            new Noty({
                                type: "success",
                                text: "Please choose upload file."
                            }).show();
                        }
                    }
                });
            }
        });

    });
</script>
