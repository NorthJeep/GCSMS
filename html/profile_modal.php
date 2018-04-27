<!-- Add Modal -->
<div class="myModal" ng-show="AddModal">
	<div class="modalContainer">
		<div class="modalHeader">
			<span class="headerTitle">Add New Member</span>
			<button class="closeBtn pull-right" ng-click="AddModal = false">&times;</button>
		</div>
		<div class="modalBody">
			<div class="form-group">
				<label>Firstname:</label>
				<input type="text" class="form-control" ng-model="firstname" id="firstname">
				<span class="pull-right input-error" ng-show="errorFirstname">{{ errorMessage }}</span>
			</div>
			<div class="form-group">
				<label>Lastname:</label>
				<input type="text" class="form-control" ng-model="lastname" id="lastname">
				<span class="pull-right input-error" ng-show="errorLastname">{{ errorMessage }}</span>
			</div>
			<div class="form-group">
				<label>Address:</label>
				<input type="text" class="form-control" ng-model="address" id="address">
				<span class="pull-right input-error" ng-show="errorAddress">{{ errorMessage }}</span>
			</div>
		</div>
		<hr>
		<div class="modalFooter">
			<div class="footerBtn pull-right">
				<button class="btn btn-default" ng-click="AddModal = false"><span class="glyphicon glyphicon-remove"></span> Cancel</button> <button class="btn btn-primary" ng-click="addnew()"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
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
				<label>Firstname:</label>
				<input type="text" class="form-control" ng-model="clickMember.firstname">
			</div>
			<div class="form-group">
				<label>Lastname:</label>
				<input type="text" class="form-control" ng-model="clickMember.lastname">
			</div>
			<div class="form-group">
				<label>Address:</label>
				<input type="text" class="form-control" ng-model="clickMember.address">
			</div>
			<div class="form-group">
				<label>Course:</label>
				<input type="text" class="form-control" ng-model="clickMember.course">
			</div>
			<div class="form-group">
				<label>Year:</label>
				<input type="text" class="form-control" ng-model="clickMember.year">
			</div>
			<div class="form-group">
				<label>Section:</label>
				<input type="text" class="form-control" ng-model="clickMember.section">
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
			<h2 class="text-center">{{clickr_stud_profile.STUD_FNAME}} {{clickr_stud_profile.STUD_LNAME}}</h2>
		</div>
		<hr>
		<div class="modalFooter">
			<div class="footerBtn pull-right">
				<button class="btn btn-default" ng-click="DeleteModal = false">
				<span class="glyphicon glyphicon-remove"></span> Cancel</button> 
				<button class="btn btn-danger" ng-click="DeleteModal = false; deleteMember(); ">
				<span class="glyphicon glyphicon-trash"></span> Yes</button>
			</div>
		</div>
	</div>
</div>
