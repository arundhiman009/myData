<div>
@if(!empty($successMsg))
<div class="alert alert-success">
    {{ $successMsg }}
</div>
@endif

<div class="row setup-content {{ $currentStep != 1 ? 'display-none' : '' }}" id="step-1">
    <div class="col-md-12">
        <div class="card-body">
            @csrf
            <div class="form-group">
                <label for="amount">Amount <span style="color:#FF0000;">*</span></label>
                <input type="text" class="form-control" wire:model="amount" id="amount" value="1" name="amount" placeholder="Enter Amount">
			</div>
			<div class="form-group">
                <!--input id="range_6" type="text" name="range_6" value=""-->
                <input type="text"  value="" class="slider" id="loadmoney">
                @error('amount') <span class="error">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="card-footer">
        <button class="btn btn-primary nextBtn btn-lg pull-right" wire:click="firstStepSubmit"
            type="button">Next</button>
        </div>    
    </div>
</div>
<div class="row setup-content {{ $currentStep != 2 ? 'display-none' : '' }}" id="step-2">
    <div class="col-md-12">
        <h3> Step 2</h3>
        <div class="form-group">
            
        </div>
        <div class="card-footer">
        <button class="btn btn-success btn-lg pull-right" wire:click="submitForm" type="button">Finish!</button>
        <button class="btn btn-danger nextBtn btn-lg pull-right" type="button" wire:click="back(1)">Back</button>
        </div>
    </div>
</div>
</div>