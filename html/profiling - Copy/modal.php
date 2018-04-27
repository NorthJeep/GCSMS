<!-- Add Modal -->
<style>
	.form-control {
    border: 1px solid #e2e2e4;
    box-shadow: none;
    color: #060606;
 }
</style>


<div class="myModal" ng-show="AddModal">
	<div class="modalContainer"><br/><br/><br/><br/>
		<div class="modalHeader">
			<span class="headerTitle">Add New Member</span>
			<button class="closeBtn pull-right" ng-click="AddModal = false">&times;</button>
		</div>
		<div class="modalBody" >
			<div class="form-group">
				<input type="text" class="form-control" ng-model="stud_no" id="stud_no" placeholder="Student Number">
				<span class="pull-right input-error" ng-show="errorFirstname">{{ errorMessage }}</span>
			</div>
			<form class="form-inline" role="form">
			<div class="form-group">
				<input type="text" class="form-control" ng-model="stud_firstname" id="stud_firstname" style="width:240px" placeholder="Firstname">
				<span class="pull-right input-error" ng-show="errorFirstname">{{ errorMessage }}</span>
			</div>
			<div class="form-group" style="padding-left:10px;">
				<input type="text" class="form-control" ng-model="stud_lastname" id="stud_lastname" style="width:240px; " placeholder="Lastname">
				<span class="pull-right input-error" ng-show="errorLastname">{{ errorMessage }}</span>
			</div>
			</form>
			<div class="form-group">
				<label style="padding-top:10px;">Course:</label>
			<div class="form-group">
				<select class="form-control input-sm m-bot15" ng-model="stud_course" id="stud_course" style="width:495px">
					<option>BSIT</option>
					<option>BSIT</option>
					<option>BSIT</option>
					<option>BSIT</option>
				</select>
			<div class="form-group" >
				<label>Year:</label>
				<label style="padding-left: 70px">Section:</label>
				<label style="padding-left: 45px">Gender:</label>

			<form class="form-inline" role="form">
			<div class="form-group">
				<select class="form-control input-sm m-bot15" ng-model="stud_yr_lvl" id="stud_yr_lvl"  style="width:90px">
					<option>1</option>
					<option>2</option>
					<option>3</option>
					<option>4</option>
				</select>
			</div>
			<div class="form-group"  style=" padding-left:10px">
				<select class="form-control input-sm m-bot15" ng-model="stud_section" id="stud_section"  style="width:90px">
					<option>1</option>
					<option>1N</option>
					<option>2</option>
					<option>3</option>
				</select>
			</div>
			<div class="form-group"  style=" padding-left:10px">
				<select class="form-control input-sm m-bot15" ng-model="stud_gender" id="stud_gender"  style="width:150px">
					<option>Female</option>
					<option>Male</option>
				</select>
			</div>
			</form>
			<form class="form-inline" role="form">
			<div class="form-group">
				<input type="text" class="form-control" ng-model="stud_email" id="stud_email" placeholder="Email" style="width:240px">
			</div>
			<div class="form-group" style="padding:10px;">
				<input type="text" class="form-control" ng-model="stud_contact_no" id="stud_contact_no" placeholder="Contact No" style="width:230px">
			</div>
			</form>
			<div class="form-group" style="padding-top:10px">
				<input type="text" class="form-control" ng-model="address" id="address" placeholder="Address">
				<span class="pull-right input-error" ng-show="errorLastname">{{ errorMessage }}</span>
			</div>
			<label>Status:</label>
			<div class="form-group">
				<select class="form-control input-sm m-bot15" ng-model="stud_status" id="stud_status"  style="width:190px">
					<option>Regular</option>
					<option>Irregular</option>
				</select>
			</div>

		</div>
		<hr>
		<div class="modalFooter">
			<div class="footerBtn pull-right">
				<button class="btn btn-default" ng-click="AddModal = false"><span class="glyphicon glyphicon-remove"></span> Cancel</button> 
				<button class="btn btn-primary" ng-click="addnew()"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
			</div>
		</div>
	</div>
</div>
</div>
<!-- Edit Modal -->
<div class="myModal" ng-show="EditModal">
	<div class="modalContainer">
		<div class="editHeader">
			<span class="headerTitle">Edit Member</span>
			<button class="closeEditBtn pull-right" ng-click="EditModal = false">&times;</button>
		</div>
		<div class="modalBody">
			<div class="form-group">
				<label>Student Number:</label>
				<input type="text" class="form-control" ng-model="clickMember.stud_no">
			</div>
			<div class="form-group">
				<label>Firstname:</label>
				<input type="text" class="form-control" ng-model="clickMember.stud_firstname">
			</div>
			<div class="form-group">
				<label>Lastname:</label>
				<input type="text" class="form-control" ng-model="clickMember.stud_lastname">
			</div>
			<div class="form-group">
				<label>Course:</label>
				<input type="text" class="form-control" ng-model="clickMember.stud_course">
			</div>
			<div class="form-group">
				<label>Year:</label>
				<input type="text" class="form-control" ng-model="clickMember.stud_yr_lvl">
			</div>
			<div class="form-group">
				<label>Section:</label>
				<input type="text" class="form-control" ng-model="clickMember.stud_section">
			</div>
		</div>
		<hr>
		<div class="modalFooter">
			<div class="footerBtn pull-right">
				<button class="btn btn-default" ng-click="EditModal = false"><span class="glyphicon glyphicon-remove"></span> Cancel</button> <button class="btn btn-success" ng-click="EditModal = false; updateMember();"><span class="glyphicon glyphicon-check"></span> Save</button>
			</div>
		</div>
	</div>
</div>

<!-- Delete Modal -->
<div class="myModal" ng-show="DeleteModal">
	<div class="modalContainer">
		<div class="deleteHeader">
			<span class="headerTitle">Delete Member</span>
			<button class="closeDelBtn pull-right" ng-click="DeleteModal = false">&times;</button>
		</div>
		<div class="modalBody">
			<h5 class="text-center">Are you sure you want to delete Member</h5>
			<h2 class="text-center">{{clickMember.firstname}} {{clickMember.lastname}}</h2>
		</div>
		<hr>
		<div class="modalFooter">
			<div class="footerBtn pull-right">
				<button class="btn btn-default" ng-click="DeleteModal = false"><span class="glyphicon glyphicon-remove"></span> Cancel</button> <button class="btn btn-danger" ng-click="DeleteModal = false; deleteMember(); "><span class="glyphicon glyphicon-trash"></span> Yes</button>
			</div>
		</div>
	</div>
</div>
