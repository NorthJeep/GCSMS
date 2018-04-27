<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="ViewModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Student Details</h4>
                </div>
                <div class="modal-body">
                <div class='twt-feed' style="background-color:#07847d; padding:15px;">
                <div class="row">
                    <div class="col-md-4">
                        <img src="images\stud.png" style=" padding-left:20px; padding-top:10px;"></img>
                        <h3> Malene Dizon </h3>
                        <h5> 2015-00394-CM-0 </h5>
                        <h5> BSIT 3-1 </h5>
                    </div>
                <div class="col-md-8">
                    <blockquote style="background-color:#03605b; height:100px;">
                        <h4>Sanction:</h4>
                        <span class="label label-warning">Warning: 18hrs</span>
                    </blockquote>
                    <blockquote style="background-color:#03605b; height:150px">
                        <h4>Counseling Remarks:</h4>
                        <h5>Follow up</h5>
                        <br/>
                    <button type="submit" class="btn btn-success" action="counseling_page.php">Start Counseling</button>
                    <button type="button" class="btn btn-info">View History</button>
                    </blockquote>
                    </div>
                    </div>
                </div>
                <div class="panel-group" id="accordion">
  <div class="panel panel-default" style=" padding-top:5px;">
    <div class="panel-heading"  style="background-color:#07847d">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse1" style="color:#FFF">
        Visit History</a>
      </h4>
    </div>
    <div id="collapse1" class="panel-collapse collapse in">
      <div class="panel-body">
          <table class="display table table-bordered table-striped" id="dynamic-table">
            <thead>
                <tr>
                    <th>Visit Purpose</th>
                    <th>Visit Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                <td>Clearance</td>
                <td>January 28, 2018</td>
                <td><button id="btnView" data-toggle="modal" href="#ViewModal" class="btn btn-primary"> <i class="fa  fa-eye"></i> View Details</button></td>
                </tr>
                <tr>
                <td>Exuse Letter</td>
                <td>February 10, 2018</td>
                <td><button id="btnView" data-toggle="modal" href="#ViewModal" class="btn btn-primary"> <i class="fa  fa-eye"></i> View Details</button></td>
                </tr>
                <tr>
                <td>CoC</td>
                <td>March 1, 2018</td>
                <td><button id="btnView" data-toggle="modal" href="#ViewModal" class="btn btn-primary"> <i class="fa  fa-eye"></i> View Details</button></td>
                </tr>
            </tbody>
          </table>
      </div>
    </div>
  </div>
  <div class="panel panel-default" style=" padding-top:5px;">
    <div class="panel-heading" style="background-color:#07847d">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse3" style="color:#FFF">
        Educational Background</a>
      </h4>
    </div>
    <div id="collapse3" class="panel-collapse collapse">
      <div class="panel-body">
        <h4 class="text-info">Primary:</h4>
            <p>Peacemaker International Christian Academy Branch</p>
        <h4 class="text-info">Secondary:</h4>
            <p>Peacemaker International Christian Academy Main</p>
        <h4 class="text-info">Tertiary:</h4>
            <p>Polytechnic University of the Philippines Quezon City</p>
        <h4 class="text-info">Others:</h4>
      </div>
    </div>
  </div>
  <div class="panel panel-default" style=" padding-top:5px;">
    <div class="panel-heading" style="background-color:#07847d">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse2" style="color:#FFF">
        Home and Family Background</a>
      </h4>
    </div>
    <div id="collapse2" class="panel-collapse collapse">
      <div class="panel-body">
          
      </div>
    </div>
  </div>
  <div class="panel panel-default" style=" padding-top:5px;">
    <div class="panel-heading" style="background-color:#07847d">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse2" style="color:#FFF">
        Health Background</a>
      </h4>
    </div>
    <div id="collapse2" class="panel-collapse collapse">
      <div class="panel-body">
        <h4 class="text-info">Physical Health:</h4>
        <h4 class="text-info">Psychological Health:</h4>
      </div>
    </div>
  </div>
</div>
                </div>
            </div>
        </div>
    </div>