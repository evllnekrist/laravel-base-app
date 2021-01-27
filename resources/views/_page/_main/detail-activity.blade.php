<form id="updateForm" onsubmit="return false;">
    <section class="users-edit">
            <h5>{{ $selected_data->card_id }}</h5>
            <div class="row">
                <div class="col-md-6 col-12 mt-1">
                    <span class="font-weight-bold">Timestamp</span><br>
                    <label class="data-info">{{ $selected_data->created_at }}</label>
                </div>
                <div class="col-md-6 col-12 mt-1">
                    <span class="font-weight-bold">Activity</span><br>
                    <label class="data-info">{{ $selected_data->transaction_code }}</label>
                </div>
            </div><hr class="data-info">
            <div class="row">
                <div class="col-md-6 col-12 mt-1">
                    <span class="font-weight-bold">First Name</span><br>
                    <label class="data-info">{{ $selected_data->member?$selected_data->member->first_name:'-' }}</label>
                </div>
                <div class="col-md-6 col-12 mt-1">
                    <span class="font-weight-bold">Last Name</span><br>
                    <label class="data-info">{{ $selected_data->member?$selected_data->member->last_name:'-' }}</label>
                </div>
            </div><hr class="data-info">
            <div class="row">
                <div class="col-md-6 col-12 mt-1">
                    <span class="font-weight-bold">Detail</span><br>
                    <label class="data-info">
                        <?php 
                            if(isset($detail)){
                                dump($detail);
                            }else{
                                echo '-';
                            } 
                        ?>
                    </label>
                </div>
                <div class="col-md-6 col-12 mt-1">
                    <span class="font-weight-bold">PIC</span><br>
                    <label class="data-info">{{ $selected_data->user->fullname }}</label>
                </div>
            </div><hr class="data-info">
    </section>
</form>
