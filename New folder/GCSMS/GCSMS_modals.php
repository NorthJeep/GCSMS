
<!--Delete Modal-->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="deactivateModal<?php echo $type?>" class="modal fade">
            <div class="modal-dialog" style="width: 30%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                        <h4 class="modal-title">Warning!</h4>
                    </div>
                    <div class="modal-body">
                        <form method="POST">
                            <br> 
                            <label style="margin-left: 15%;">You are about to deactivate a data,</label><br>
                            <label style="margin-left: 15%;">continue?</label>
                            <br><br>
                            <div style="margin-left: 60%">
                                <button type="submit" class="btn btn-cancel" data-dismiss="modal">CANCEL</button>
                                <button type="submit" class="btn btn-info" name="deactivate">OK</button>
                            </div>     
                        </form>    
                    </div>
                </div>
            </div>
        </div>

