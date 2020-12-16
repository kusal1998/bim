@inject('UtilityService', 'App\Services\UtilityService')
@if(Request::segment(2)!='view' )
{{-- @if($UtilityService->getAccessRegReject(Request::segment(1))=='Yes')
<button type="button" id="archive" name="button" value="archive" class="btn btn-danger">Archive File</button>
@endif --}}
@if($UtilityService->getAccessPubVerify(Request::segment(1))=='Yes')
@if($form12)
@if($form12->current_stage=='Publication verify')
<button class="btn btn-primary" type="submit" name="button" value="forward_asst_comm">Forward to Assistant Commissioner </button>
@endif
@endif
@endif
@if($UtilityService->getAccessAsstComm(Request::segment(1))=='Yes')
<a href="javascript:;" data-toggle="modal" data-target="#ComputerModal" class="btn btn-primary">Forward to Bimsaviya Commissioner </a>
@endif
@if($UtilityService->getAccessBimsaviyaComm(Request::segment(1))=='Yes')
<button class="btn btn-primary" type="submit" name="button" value="forward_comm_general">Forward to Commissioner General </button>
@endif
@if($UtilityService->getAccessCommGen(Request::segment(1))=='Yes')
<button class="btn btn-primary" type="submit" name="button" value="comm_general_approval">Approve </button>
@endif
@if($UtilityService->getAccessForwardProof(Request::segment(1))=='Yes')
<a href="{{url('/12th-sentence-file/download/'.$form12->id)}}" class="btn btn-primary">Download Text File</a>
@if($form12)
@if($form12->current_stage=='Proof read(Sinhala)-Computer'||$form12->current_stage=='Computer branch')
<button class="btn btn-primary" type="submit" name="button" value="forward_to_proof_read">Forward to Proof Read (Sinhala) </button>
@endif
@endif
@endif
@if($UtilityService->getAccessProof(Request::segment(1))=='Yes')
@if($form12)
@if($form12->current_stage=='Proof read(Sinhala)')
<button class="btn btn-primary" type="submit" name="button" value="proof_read_sinhala">Forward to Computer </button>
@endif
@endif
@endif
@if($UtilityService->getAccessForwardTransProof(Request::segment(1))=='Yes')
@if($form12)
@if($form12->current_stage=='Proof read(Sinhala)-Computer'|| $form12->current_stage=='Proof read(Translation)-Computer')
<button class="btn btn-primary" type="submit" name="button" value="forward_to_proof_read_translation">Forward to Proof Read (Tamil/English) </button>
@endif
@if($form12->current_stage=='Proof read(Translation)-Computer')
<button class="btn btn-primary" type="submit" name="button" value="proof_read_complete">Complete Proof Read </button>
@endif
@endif
@endif
@if($UtilityService->getAccessTransProof(Request::segment(1))=='Yes')
@if($form12)
@if($form12->current_stage=='Proof read(Translates)')
<button class="btn btn-primary" type="submit" name="button" value="proof_read_translation">Forward to Computer </button>
@endif
@endif
@endif
@if($UtilityService->getAccessForwardPublication(Request::segment(1))=='Yes')
@if($form12)
@if($form12->current_stage=='Proof read complete')
<button class="btn btn-primary" type="submit" name="button" value="publication_without_G">Forward to Publication </button>
@endif
@if($form12->current_stage=='Gazette with G')
<button class="btn btn-primary" type="submit" name="button" value="publication_with_G">Forward to Pub with Gazette Number</button>
@endif
@endif
@endif
@if($UtilityService->getAccessForwardPress(Request::segment(1))=='Yes')
@if($form12)
@if($form12->current_stage=='Publication without G')
<button class="btn btn-primary" type="submit" name="button" value="press_without_G">Forward Gov't Press </button>
@endif
@if($form12->current_stage=='Publication with G')
<button class="btn btn-primary" type="submit" name="button" value="press_with_G">Finalized Gazette to Press </button>
@endif
@if($form12->current_stage=='Gov press with G')
<button class="btn btn-primary" type="submit" name="button" value="online">Publish Online </button>
@endif
@endif
@endif
@if($UtilityService->getAccessGazette(Request::segment(1))=='Yes')
@if($form12)
@if($form12->current_stage=='Gov Press without G')
<a href="javascript:;" data-toggle="modal" data-target="#GazetteModal" class="btn btn-primary">Send Gazetted Number </a>
@endif
@endif
@endif
@if($UtilityService->getAccessCertificate(Request::segment(1))=='Yes')
@if($form12)
@if($form12->current_stage=='Online Publish')
<button class="btn btn-primary" type="submit" name="button" value="certificates">Certificates Entered </button>
@endif
@endif
@endif
@endif
