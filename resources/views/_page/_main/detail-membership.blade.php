<form id="updateForm" onsubmit="return false;">

    <div class="col-md-12 col-12 page-users-view">
        <div class="row">
            <div class="col-md-5 col-12 mt-1">
                <span class="font-weight-bold">First Name</span><br>
                <label class="data-info">{{ $selected_data->first_name }}</label>
                <div class="data-edit hidden">
                    <input type="text" value="{{ $selected_data->first_name }}" class="form-control" placeholder="Enter First Name" name="first_name" maxlength="50" required>
                </div>
            </div>
            <div class="col-md-7 col-12 mt-1">
                <span class="font-weight-bold">Last Name</span><br>
                <label class="data-info">{{ $selected_data->last_name }}</label>
                <div class="data-edit hidden">
                    <input type="text" value="{{ $selected_data->last_name }}" class="form-control" placeholder="Enter Last Name" name="last_name" maxlength="50" required>
                </div>
            </div>
        </div><hr class="data-info">
        <div class="row">
            <div class="col-md-5 col-12 mt-1">
                <span class="font-weight-bold">Role</span><br>
                <label class="data-info">{{ $selected_data->role->name }}</label>
                <div class="data-edit hidden">
                    <select id="role_edit_selector" name="member_role_id" class="select2 form-control" style="width: 100%" required>
                        @foreach($list_role as $role)
                            <option @if($selected_data->member_role_id==$role->id) selected @endif value="{{$role->id}}">{{$role->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-7 col-12 mt-1">
                <span class="font-weight-bold">Status</span><br>
                <label class="data-info">{{ $selected_data->status->name }}</label>
                <div class="data-edit hidden">
                    <select id="status_edit_selector" name="status_code" class="select2 form-control" style="width: 100%" required>
                        @foreach($list_status as $status)
                            <option @if($selected_data->status_code==$status->code) selected @endif value="{{$status->code}}">{{$status->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div><hr class="data-info">
        <div class="row">
            <div class="col-md-5 col-12 mt-1">
                <span class="font-weight-bold">KTP Number</span><br>
                <label class="data-info">{{ $selected_data->ktp_number }}</label>
                <div class="data-edit hidden">
                    <input type="text" value="{{ $selected_data->ktp_number }}" class="form-control" placeholder="Enter KTP Number" name="ktp_number" maxlength="16" required>
                </div>
            </div>
            <div class="col-md-7 col-12 mt-1">
                <span class="data-info font-weight-bold">KTP File</span><br>
                <label class="data-info">
                    @if($selected_data->ktp_file)
                    {{ $selected_data->ktp_file }}
                    @else
                    no documents uploaded
                    @endif
                </label>
                <div class="data-edit hidden">
                    <input type="file" class="form-control" placeholder="Enter KTP File" name="ktp_file" style="border:none;">
                </div>
            </div>
        </div><hr class="data-info">
        <div class="row">
            <div class="col-md-5 col-12 mt-1">
                <span class="font-weight-bold">Place of Birth</span><br>
                <label class="data-info">{{ $selected_data->pob }}</label>
                <div class="data-edit hidden">
                    <input type="text" value="{{ $selected_data->pob }}" class="form-control" placeholder="Enter PoB" name="pob" maxlength="20" required>
                </div>
            </div>
            <div class="col-md-7 col-12 mt-1">
                <span class="font-weight-bold">Date of Birth</span><br>
                <label class="data-info">{{ $selected_data->dob }}</label>
                <div class="data-edit hidden">
                    <input type="text" value="{{ $selected_data->dob }}" class="form-control" placeholder="Enter DoB" name="dob" required>
                </div>
            </div>
        </div><hr class="data-info">
        <div class="row">
            <div class="col-md-5 col-12 mt-1">
                <span class="font-weight-bold">Email</span><br>
                <label class="data-info">{{ $selected_data->email }}</label>
                <div class="data-edit hidden">
                    <input type="text" value="{{ $selected_data->email }}" class="form-control" placeholder="Enter Email" name="email" maxlength="50" required>
                </div>
            </div>
            <div class="col-md-4 col-12 mt-1">
                <span class="font-weight-bold">Phone Number</span><br>
                <label class="data-info">{{ $selected_data->phone }}</label>
                <div class="data-edit hidden">
                    <input type="text" value="{{ $selected_data->phone }}" class="form-control" placeholder="Enter Phone Number" name="phone" maxlength="20" required>
                </div>
            </div>
            <div class="col-md-3 col-12 mt-1">
                <span class="font-weight-bold">Gender</span><br>
                <label class="data-info">{{ $selected_data->gender->name }}</label>
                <div class="data-edit hidden">
                    <select id="gender_edit_selector" name="gender_code" class="select2 form-control" style="width: 100%" required>
                        @foreach($list_gender as $gender)
                            <option @if($selected_data->gender_code==$gender->code) selected @endif value="{{$gender->code}}">{{$gender->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div><hr class="data-info">
        <div class="row">
            <div class="col-md-5 col-12 mt-1">
                <span class="font-weight-bold">Province</span><br>
                <label class="data-info">{{ $selected_data->province }}</label>
                <div class="data-edit hidden">
                    <input type="text" value="{{ $selected_data->province }}" class="form-control" placeholder="Enter Province" name="province" maxlength="30" required>
                </div>
            </div>
            <div class="col-md-4 col-12 mt-1">
                <span class="font-weight-bold">City</span><br>
                <label class="data-info">{{ $selected_data->city }}</label>
                <div class="data-edit hidden">
                    <input type="text" value="{{ $selected_data->city }}" class="form-control" placeholder="Enter City" name="city" maxlength="30" required>
                </div>
            </div>
            <div class="col-md-3 col-12 mt-1">
                <span class="font-weight-bold">Post Code</span><br>
                <label class="data-info">{{ $selected_data->post_code }}</label>
                <div class="data-edit hidden">
                    <input type="text" value="{{ $selected_data->post_code }}" class="form-control" placeholder="Enter Post Code" name="post_code" maxlength="5" required>
                </div>
            </div>
        </div><hr class="data-info">
        <div class="row">
            <div class="col-md-12 col-12 mt-1">
                <span class="font-weight-bold">Address</span><br>
                <label class="data-info">{{ $selected_data->address }}</label>
                <div class="data-edit hidden">
                    <textarea rows="4" class="form-control" placeholder="Enter Address" name="address" maxlength="200" required>{{ $selected_data->address }}</textarea>
                </div>
            </div>
        </div>
        <input type="text" value="{{ $hash }}" name="id" hidden>
        <div class="row">
            <div class="col-md-12 col-12 mt-1">
                <span class="font-weight-bold"></span><br>
                <label class="data-info"></label>
                <div class="data-edit hidden">
                    <input type="submit" id="button-delete" class="btn btn-dark hidden" value="Delete"/>
                    <input type="submit" id="button-update" class="btn btn-primary hidden" value="Update"/>
                </div>
            </div>
        </div>
    </div>

</form>
