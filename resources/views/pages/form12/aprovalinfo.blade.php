@if($form12!=null)
@if(Request::segment(1)=='12th-sentence')
<div class="row">
        <div class="col-md-4">
          <address>
            @if($form12->prepared_by)<strong>{{trans('sentence.prepared_by')}}</strong> <br>
          {{$UtilityService->getUserName($form12->prepared_by)}} ({{$form12->prepared_date}})<br>@endif

            @if($form12->publication_branch==1)<strong>{{trans('sentence.publication_checked_by')}}</strong>  <br>
            {{$UtilityService->getUserName($form12->publication_checked_by)}} ({{$form12->publication_branch_date}})<br>@endif
            @if($form12->asst_comm_approval!=null)<strong>{{trans('sentence.assistant_commisioner_approval_by')}}</strong>  <br>
            {{$UtilityService->getUserName($form12->asst_comm_approval)}} @if($form12->asst_comm_approval_date!=null)({{$form12->asst_comm_approval_date}})@endif <br>@endif

            @if($form12->comm_gen_approval!=null)<strong>{{trans('sentence.commisioner_general_approval_by')}}</strong>  <br>
            {{$UtilityService->getUserName($form12->comm_gen_approval)}} ({{$form12->comm_gen_approal_date}})<br>@endif

          </address>
        </div>
        <div class="col-md-4">
          <address>
            @if($form12->regional_approved==1)<strong>{{trans('sentence.regional_office_approved_by')}} </strong> <br>
            {{$UtilityService->getUserName($form12->regional_approved_by)}} ({{$form12->regional_approved_date}})<br>@endif
            @if($form12->computer_checked_by!=null)<strong>{{trans('sentence.computer_checked_by')}}</strong>  <br>
           {{$UtilityService->getUserName($form12->computer_checked_by)}} @if($form12->computer_branch_date!=null) ({{$form12->computer_branch_date}})@endif <br>@endif
           @if($form12->bimsaviya_approval!=null)<strong>{{trans('sentence.bimsaviya_commisioner_approval_by')}}</strong> <br>
            {{$UtilityService->getUserName($form12->bimsaviya_approval)}} ({{$form12->bimsaviya_approval_date}})<br>@endif

          </address>
        </div>
        <div class="col-md-4">
                <address>
                @foreach($proof_reads as $proof_read)
                  <strong>{{trans('sentence.1st_proof_read_by')}}</strong> <br>
                  {{$UtilityService->getUserName($proof_read->proof_read_by)}} ({{$proof_read->proof_read_date}})<br>
                @endforeach
                </address>
              </div>

      </div>
      <div class="row">
            <div class="col-md-4">
              <address>
                @foreach($proof_reads_translate as $proof_read)
                <strong>{{trans('sentence.1st_english_proof_read_by')}} </strong> <br>
                {{$UtilityService->getUserName($proof_read->proof_read_by)}} ({{$proof_read->proof_read_date}})<br>
                @endforeach
              </address>
            </div>
            <div class="col-md-4">
                    <address>
                        {{-- Proof read complete --}}
                      
                      @if($form12->first_proof_read==1)<strong>{{trans('sentence.1st_tamil_proof_read_by')}}</strong> <br>
                      {{$UtilityService->getUserName($form12->first_proof_read_by)}} ({{$form12->first_prrof_read_date}})<br>@endif
                      {{-- Publication to press without G --}}
                      @if($form12->first_proof_english==1)<strong>{{trans('sentence.corrected_after_1st_english_read_by')}}</strong>  <br>
                     {{$UtilityService->getUserName($form12->first_proof_english_by)}} ({{$form12->first_proof_english_date}})<br>@endif
                     {{-- Computer to Publication with G --}}
                      @if($form12->first_proof_read_tamil==1)<strong>{{trans('sentence.corrected_after_1st_tamil_read_by')}}</strong>  <br>
                     {{$UtilityService->getUserName($form12->first_proof_read_tamil_by)}} ({{$form12->first_prrof_read_tamil_date}})<br>@endif
                    </address>
                  </div>
                  <div class="col-md-4">
                        <address>
                            {{-- Computer to Publication without G --}}
                          @if($form12->second_proof_read==1)<strong>{{trans('sentence.corrected_after_2nd_read_by')}}</strong> <br>
                          {{$UtilityService->getUserName($form12->second_proof_read_by)}} ({{$form12->second_proof_read_date}})<br>@endif
                          {{-- Publication to Computer with G --}}
                          @if($form12->second_proof_english==1)<strong>{{trans('sentence.corrected_after_2nd_english_read_by')}}</strong>  <br>
                          {{$UtilityService->getUserName($form12->second_proof_english_by)}} ({{$form12->second_proof_english_date}})<br>@endif
                          {{-- Publication to Press with G --}}
                          @if($form12->sent_gov_press!=null)<strong>{{trans('sentence.corrected_after_2nd_tamil_read_by')}}</strong> <br>
                          {{$UtilityService->getUserName($form12->sent_gov_press)}} ({{$form12->sent_gov_press_date}})<br>@endif
                          {{-- <strong>{{trans('sentence.sent_to_goverment_press_by')}}</strong> <br>
                          @if($form12->bimsaviya_approval==1){{$UtilityService->getUserName($form12->bimsaviya_approval)}} ({{$form12->bimsaviya_approval_date}})@endif<br> --}}
                        </address>
                      </div>

                      <div class="col-md-4">
                          <address>
                              <strong>Recheck History </strong> <br>
                            @foreach($recheckHistory as $element)
                            
                          {{$UtilityService->getUserName($element->recheck_by)}} ({{$element->created_at}}) - {{$element->recheck_reason}}<br>
                            @endforeach
                          </address>
                        </div>
          </div>
@endif
@if(Request::segment(1)=='14th-sentence')
<div class="row">
        <div class="col-md-4">
          <address>
            @if($form12->prepared_by)<strong>{{trans('sentence.prepared_by')}}</strong> <br>
          {{$UtilityService->getUserName($form12->prepared_by)}} ({{$form12->prepared_date}})<br>@endif

            @if($form12->publication_checked==1)<strong>{{trans('sentence.publication_checked_by')}}</strong>  <br>
            {{$UtilityService->getUserName($form12->publication_checked_by)}} ({{$form12->publication_checked_date}})<br>@endif
            @if($form12->asst_commissioner_approval!=null)<strong>{{trans('sentence.assistant_commisioner_approval_by')}}</strong>  <br>
            {{$UtilityService->getUserName($form12->asst_commissioner_approval)}} @if($form12->asst_commissioner_approved_date!=null)({{$form12->asst_commissioner_approved_date}})@endif <br>@endif

            @if($form12->comm_gen_approval!=null)<strong>{{trans('sentence.commisioner_general_approval_by')}}</strong>  <br>
            {{$UtilityService->getUserName($form12->comm_gen_approval)}} ({{$form12->comm_gen_approval_date}})<br>@endif

          </address>
        </div>
        <div class="col-md-4">
          <address>
            @if($form12->regional_approved==1)<strong>{{trans('sentence.regional_office_approved_by')}} </strong> <br>
            {{$UtilityService->getUserName($form12->regional_approved_by)}} ({{$form12->regional_approved_date}})<br>@endif
            @if($form12->computer_branch)<strong>{{trans('sentence.computer_checked_by')}}</strong>  <br>
           {{$UtilityService->getUserName($form12->computer_branch)}} @if($form12->computer_branch_date!=null) ({{$form12->computer_branch_date}})@endif <br>@endif
            @if($form12->bimsaviya_commissioner_approval!=null)<strong>{{trans('sentence.bimsaviya_commisioner_approval_by')}}</strong> <br>
            {{$UtilityService->getUserName($form12->bimsaviya_commissioner_approval)}} ({{$form12->bimsaviya_commissioner_approved_date}})<br>@endif
          </address>
        </div>
        <div class="col-md-4">
                <address>
               @foreach($proof_reads as $proof_read)
                  <strong>{{trans('sentence.1st_proof_read_by')}}</strong> <br>
                  {{$UtilityService->getUserName($proof_read->proof_read_by)}} ({{$proof_read->proof_read_date}})<br>
                @endforeach
                </address>
              </div>

      </div>
      <div class="row">
            <div class="col-md-4">
              <address>
                 @foreach($proof_reads_translate as $proof_read)
                <strong>{{trans('sentence.1st_english_proof_read_by')}} </strong> <br>
                {{$UtilityService->getUserName($proof_read->proof_read_by)}} ({{$proof_read->proof_read_date}})<br>
                @endforeach
              </address>
            </div>
            <div class="col-md-4">
                    <address>
                        {{-- Proof read complete --}}
                      @if($form12->proof_read_complete!=null)<strong>{{trans('sentence.1st_tamil_proof_read_by')}}</strong> <br>
                      {{$UtilityService->getUserName($form12->proof_read_complete)}} ({{$form12->proof_read_complete_date}})<br>@endif
                      {{-- Publication to press without G --}}
                      @if($form12->press_without==1)<strong>{{trans('sentence.corrected_after_1st_english_read_by')}}</strong>  <br>
                     {{$UtilityService->getUserName($form12->press_without_by)}} ({{$form12->press_without_date}})<br>@endif
                     {{-- Computer to Publication with G --}}
                      @if($form12->gazette_with==1)<strong>{{trans('sentence.corrected_after_1st_tamil_read_by')}}</strong>  <br>
                     {{$UtilityService->getUserName($form12->gazette_with_by)}} ({{$form12->gazette_with_date}})<br>@endif
                    </address>
                  </div>
                  <div class="col-md-4">
                        <address>
                            {{-- Computer to Publication without G --}}
                          @if($form12->gazette_without==1)<strong>{{trans('sentence.corrected_after_2nd_read_by')}}</strong> <br>
                          {{$UtilityService->getUserName($form12->gazette_without_by)}} ({{$form12->gazette_without_date}})<br>@endif
                          {{-- Publication to Computer with G --}}
                          @if($form12->computer_with==1)<strong>{{trans('sentence.corrected_after_2nd_english_read_by')}}</strong>  <br>
                          {{$UtilityService->getUserName($form12->computer_with_by)}} ({{$form12->computer_with_date}})<br>@endif
                          {{-- Publication to Press with G --}}
                          @if($form12->sent_gov_press!=null)<strong>{{trans('sentence.corrected_after_2nd_tamil_read_by')}}</strong> <br>
                          {{$UtilityService->getUserName($form12->sent_gov_press)}} ({{$form12->sent_gov_press_date}})<br>@endif
                          {{-- <strong>{{trans('sentence.sent_to_goverment_press_by')}}</strong> <br>
                          @if($form12->bimsaviya_approval==1){{$UtilityService->getUserName($form12->bimsaviya_approval)}} ({{$form12->bimsaviya_approval_date}})@endif<br> --}}
                        </address>
                      </div>
          </div>
@endif
@if(Request::segment(1)=='55th-sentence')
<div class="row">
        <div class="col-md-4">
          <address>
            @if($form12->prepared_by)<strong>{{trans('sentence.prepared_by')}}</strong> <br>
          {{$UtilityService->getUserName($form12->prepared_by)}} ({{$form12->prepared_date}})<br>@endif

            @if($form12->publication_checked!=null)<strong>{{trans('sentence.publication_checked_by')}}</strong>  <br>
            {{$UtilityService->getUserName($form12->publication_checked)}} ({{$form12->publication_checked_date}})<br>@endif
            @if($form12->asst_com_approval!=null)<strong>{{trans('sentence.assistant_commisioner_approval_by')}}</strong>  <br>
            {{$UtilityService->getUserName($form12->asst_com_approval)}} @if($form12->asst_com_approval_date!=null)({{$form12->asst_comm_approval_date}})@endif <br>@endif

            @if($form12->commissioner_general_approval!=null)<strong>{{trans('sentence.commisioner_general_approval_by')}}</strong>  <br>
            {{$UtilityService->getUserName($form12->commissioner_general_approval)}} ({{$form12->commissioner_general_approval_date}})<br>@endif

          </address>
        </div>
        <div class="col-md-4">
          <address>
            @if($form12->regional_officer_approval!=null)<strong>{{trans('sentence.regional_office_approved_by')}} </strong> <br>
            {{$UtilityService->getUserName($form12->regional_officer_approval)}} ({{$form12->regional_officer_approval_date}})<br>@endif
            @if($form12->computer_checked==1)<strong>{{trans('sentence.computer_checked_by')}}</strong>  <br>
           {{$UtilityService->getUserName($form12->computer_checked_by)}} ({{$form12->computer_checked_date}})<br>@endif
            @if($form12->bimsaviya_com_approval!=null)<strong>{{trans('sentence.bimsaviya_commisioner_approval_by')}}</strong> <br>
            {{$UtilityService->getUserName($form12->bimsaviya_com_approval)}} ({{$form12->bimsaviya_approval_date}})<br>@endif

          </address>
        </div>
        <div class="col-md-4">
                <address>
                @foreach($proof_reads as $proof_read)
                  <strong>{{trans('sentence.1st_proof_read_by')}}</strong> <br>
                  {{$UtilityService->getUserName($proof_read->proof_read_by)}} ({{$proof_read->proof_read_date}})<br>
                @endforeach
                </address>
              </div>

      </div>
      <div class="row">
            <div class="col-md-4">
              <address>
                  @foreach($proof_reads_translate as $proof_read)
                <strong>{{trans('sentence.1st_english_proof_read_by')}} </strong> <br>
                {{$UtilityService->getUserName($proof_read->proof_read_by)}} ({{$proof_read->proof_read_date}})<br>
                @endforeach
              </address>
            </div>
            <div class="col-md-4">
                    <address>
                        {{-- Proof read complete --}}
                      @if($form12->proof_read_complete!=null)<strong>{{trans('sentence.1st_tamil_proof_read_by')}}</strong> <br>
                      {{$UtilityService->getUserName($form12->proof_read_complete)}} ({{$form12->proof_read_complete_date}})<br>@endif
                      {{-- Publication to press without G --}}
                      @if($form12->press_without==1)<strong>{{trans('sentence.corrected_after_1st_english_read_by')}}</strong>  <br>
                     {{$UtilityService->getUserName($form12->press_without_by)}} ({{$form12->press_without_date}})<br>@endif
                     {{-- Computer to Publication with G --}}
                      @if($form12->gazette_with==1)<strong>{{trans('sentence.corrected_after_1st_tamil_read_by')}}</strong>  <br>
                     {{$UtilityService->getUserName($form12->gazette_with_by)}} ({{$form12->gazette_with_date}})<br>@endif
                    </address>
                  </div>
                  <div class="col-md-4">
                        <address>
                            {{-- Computer to Publication without G --}}
                          @if($form12->gazette_without==1)<strong>{{trans('sentence.corrected_after_2nd_read_by')}}</strong> <br>
                          {{$UtilityService->getUserName($form12->gazette_without_by)}} ({{$form12->gazette_without_date}})<br>@endif
                          {{-- Publication to Computer with G --}}
                          @if($form12->computer_with==1)<strong>{{trans('sentence.corrected_after_2nd_english_read_by')}}</strong>  <br>
                          {{$UtilityService->getUserName($form12->computer_with_by)}} ({{$form12->computer_with_date}})<br>@endif
                          {{-- Publication to Press with G --}}
                          @if($form12->sent_to_press!=null)<strong>{{trans('sentence.corrected_after_2nd_tamil_read_by')}}</strong> <br>
                          {{$UtilityService->getUserName($form12->sent_to_press)}} ({{$form12->sent_to_press_date}})<br>@endif
                          {{-- <strong>{{trans('sentence.sent_to_goverment_press_by')}}</strong> <br>
                          @if($form12->bimsaviya_approval==1){{$UtilityService->getUserName($form12->bimsaviya_approval)}} ({{$form12->bimsaviya_approval_date}})@endif<br> --}}
                        </address>
                      </div>
          </div>
@endif
@if(Request::segment(1)=='amendments')
<div class="row">
        <div class="col-md-4">
          <address>
            @if($form12->prepared_by)<strong>{{trans('sentence.prepared_by')}}</strong> <br>
          {{$UtilityService->getUserName($form12->prepared_by)}} ({{$form12->prepared_date}})<br>@endif

            @if($form12->publication_verify==1)<strong>{{trans('sentence.publication_checked_by')}}</strong>  <br>
            {{$UtilityService->getUserName($form12->publication_verify_by)}} ({{$form12->publication_verify_date}})<br>@endif
            @if($form12->asst_com_approval!=null)<strong>{{trans('sentence.assistant_commisioner_approval_by')}}</strong>  <br>
            {{$UtilityService->getUserName($form12->asst_com_approval)}} @if($form12->asst_com_approval_date!=null)({{$form12->asst_comm_approval_date}})@endif <br>@endif

            @if($form12->commissioner_general_apprival!=null)<strong>{{trans('sentence.commisioner_general_approval_by')}}</strong>  <br>
            {{$UtilityService->getUserName($form12->commissioner_general_apprival)}} ({{$form12->commissioner_general_apprival_date}})<br>@endif

          </address>
        </div>
        <div class="col-md-4">
          <address>
            @if($form12->regional_office_approval!=null)<strong>{{trans('sentence.regional_office_approved_by')}} </strong> <br>
            {{$UtilityService->getUserName($form12->regional_office_approval)}} ({{$form12->regional_office_approved_date}})<br>@endif
            @if($form12->computer_checked==1)<strong>{{trans('sentence.computer_checked_by')}}</strong>  <br>
           {{$UtilityService->getUserName($form12->computer_checked_by)}} @if($form12->computer_checked_date!=null) ({{$form12->computer_checked_date}})@endif <br>@endif
            @if($form12->bimsaviya_com_approval!=null)<strong>{{trans('sentence.bimsaviya_commisioner_approval_by')}}</strong> <br>
            {{$UtilityService->getUserName($form12->bimsaviya_com_approval)}} ({{$form12->bimsaviya_com_approval_date}})<br>@endif

          </address>
        </div>
        <div class="col-md-4">
                <address>
                @foreach($proof_reads as $proof_read)
                  <strong>{{trans('sentence.1st_proof_read_by')}}</strong> <br>
                  {{$UtilityService->getUserName($proof_read->proof_read_by)}} ({{$proof_read->proof_read_date}})<br>
                @endforeach
                </address>
              </div>

      </div>
      <div class="row">
            <div class="col-md-4">
              <address>
                 @foreach($proof_reads_translate as $proof_read)
                <strong>{{trans('sentence.1st_english_proof_read_by')}} </strong> <br>
                {{$UtilityService->getUserName($proof_read->proof_read_by)}} ({{$proof_read->proof_read_date}})<br>
                @endforeach
              </address>
            </div>
            <div class="col-md-4">
                    <address>
                        {{-- Proof read complete --}}
                      @if($form12->proof_read_complete_by!=null)<strong>{{trans('sentence.1st_tamil_proof_read_by')}}</strong> <br>
                      {{$UtilityService->getUserName($form12->proof_read_complete_by)}} ({{$form12->proof_read_complete_date}})<br>@endif
                      {{-- Publication to press without G --}}
                      @if($form12->press_without==1)<strong>{{trans('sentence.corrected_after_1st_english_read_by')}}</strong>  <br>
                     {{$UtilityService->getUserName($form12->press_without_by)}} ({{$form12->press_without_date}})<br>@endif
                     {{-- Computer to Publication with G --}}
                      @if($form12->gazette_with==1)<strong>{{trans('sentence.corrected_after_1st_tamil_read_by')}}</strong>  <br>
                     {{$UtilityService->getUserName($form12->gazette_with_by)}} ({{$form12->gazette_with_date}})<br>@endif
                    </address>
                  </div>
                  <div class="col-md-4">
                        <address>
                            {{-- Computer to Publication without G --}}
                          @if($form12->gazette_without==1)<strong>{{trans('sentence.corrected_after_2nd_read_by')}}</strong> <br>
                          {{$UtilityService->getUserName($form12->gazette_without_by)}} ({{$form12->gazette_without_date}})<br>@endif
                          {{-- Publication to Computer with G --}}
                          @if($form12->computer_with==1)<strong>{{trans('sentence.corrected_after_2nd_english_read_by')}}</strong>  <br>
                          {{$UtilityService->getUserName($form12->computer_with_by)}} ({{$form12->computer_with_date}})<br>@endif
                          {{-- Publication to Press with G --}}
                          @if($form12->sent_to_gov_press!=null)<strong>{{trans('sentence.corrected_after_2nd_tamil_read_by')}}</strong> <br>
                          {{$UtilityService->getUserName($form12->sent_to_gov_press)}} ({{$form12->sent_to_gov_press_date}})<br>@endif
                          {{-- <strong>{{trans('sentence.sent_to_goverment_press_by')}}</strong> <br>
                          @if($form12->bimsaviya_approval==1){{$UtilityService->getUserName($form12->bimsaviya_approval)}} ({{$form12->bimsaviya_approval_date}})@endif<br> --}}
                        </address>
                      </div>
          </div>
@endif
@endif
