@php
    $total=0;
    $total=($DashboardService->getF14CurrentCount()+$DashboardService->get14gazetted());


@endphp
<div class="col-lg-6 mg-t-10">
        <div class="card">
          <div class="card-header d-flex align-items-start justify-content-between">
            <h6 class="lh-5 mg-b-0">14<sup>th</sup> Sentence</h6>
            {{-- <a href="" class="tx-13 link-03">Mar 01 - Mar 20, 2019 <i class="icon ion-ios-arrow-down"></i></a> --}}
          </div><!-- card-header -->
          <div class="card-body pd-y-15 pd-x-10">
            <div class="table-responsive">
              <table class="table table-borderless table-sm tx-13 tx-nowrap mg-b-0">
                <thead>
                  <tr class="tx-10 tx-spacing-1 tx-color-03 tx-uppercase">
                    <th class="wd-5p">Link</th>
                    <th>Category</th>
                    <th class="text-right">Percentage (%)</th>
                    <th class="text-right">Value (%)</th>
                    <th class="text-right">Value (Numerical)</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="align-middle text-center"><a href="/14th-sentence/new-requests"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-external-link wd-12 ht-12 stroke-wd-3"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg></a></td>
                    <td class="align-middle tx-medium">New Requests</td>
                    <td class="align-middle text-right">
                      <div class="wd-100 d-inline-block">
                        <div class="progress ht-4 mg-b-0">
                          <div class="progress-bar bg-teal" style="width:{{$DashboardService->getPre($DashboardService->getF14NewCount(),$total)}}%"  role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                      </div>
                    </td>
                    <td class="align-middle text-right"><span class="tx-medium">{{$DashboardService->getPre($DashboardService->getF14NewCount(),$total)}}%</span></td>
                    <td class="align-middle text-right"><span class="tx-medium">{{$DashboardService->getF14NewCount()}}</span></td>
                  </tr>
                  <tr>
                    <td class="align-middle text-center"><a href="/14th-sentence/approved-requests"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-external-link wd-12 ht-12 stroke-wd-3"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg></a></td>
                    <td class="align-middle tx-medium">Current Files</td>
                    <td class="align-middle text-right">
                      <div class="wd-100 d-inline-block">
                        <div class="progress ht-4 mg-b-0">
                          <div class="progress-bar bg-primary" style="width:{{$DashboardService->getPre($DashboardService->getF14CurrentCount(),$total)}}%" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                      </div>
                    </td>
                  <td class="text-right"><span class="tx-medium">{{$DashboardService->getPre($DashboardService->getF14CurrentCount(),$total)}}%</span></td>
                    <td class="align-middle text-right"><span class="tx-medium">{{$DashboardService->getF14CurrentCount()}}</span></td>
                  </tr>
                  <tr>
                    <td class="align-middle text-center"><a href="/14th-sentence/pending-requests"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-external-link wd-12 ht-12 stroke-wd-3"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg></a></td>
                    <td class="align-middle tx-medium">Pending Approval</td>
                    <td class="align-middle text-right">
                      <div class="wd-100 d-inline-block">
                        <div class="progress ht-4 mg-b-0">
                          <div class="progress-bar bg-warning" style="width:{{$DashboardService->getPre($DashboardService->getF14PendingCount(),$total)}}%" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                      </div>
                    </td>
                    <td class="text-right"><span class="tx-medium">{{$DashboardService->getPre($DashboardService->getF14PendingCount(),$total)}}%</span></td>
                    <td class="align-middle text-right"><span class="tx-medium">{{$DashboardService->getF14PendingCount()}}</span></td>
                  </tr>
                  <tr>
                    <td class="align-middle text-center"><a href="/14th-sentence/rejected-requests"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-external-link wd-12 ht-12 stroke-wd-3"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg></a></td>
                    <td class="align-middle tx-medium">Rejected Requests</td>
                    <td class="align-middle text-right">
                      <div class="wd-100 d-inline-block">
                        <div class="progress ht-4 mg-b-0">
                          <div class="progress-bar bg-pink" style="width:{{$DashboardService->getPre($DashboardService->getF14RejectedCount(),$total)}}%"  role="progressbar" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                      </div>
                    </td>
                    <td class="text-right"><span class="tx-medium">{{$DashboardService->getPre($DashboardService->getF14RejectedCount(),$total)}}%</span></td>
                    <td class="align-middle text-right"><span class="tx-medium">{{$DashboardService->getF14RejectedCount()}}</span></td>
                  </tr>
                  <tr>
                        <td class="align-middle text-center"><a href="/14th-sentence/gazetted-requests"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-external-link wd-12 ht-12 stroke-wd-3"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg></a></td>

                    <td class="align-middle tx-medium">Gazetted Requests</td>
                    <td class="align-middle text-right">
                      <div class="wd-100 d-inline-block">
                        <div class="progress ht-4 mg-b-0">
                            <div class="progress-bar bg-teal" style="width:{{$DashboardService->getPre($DashboardService->get14gazetted(),$total)}}%"  role="progressbar" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                      </div>
                    </td>
                    <td class="text-right"><span class="tx-medium">{{$DashboardService->getPre($DashboardService->get14gazetted(),$total)}}%</span></td>
                    <td class="align-middle text-right"><span class="tx-medium">{{$DashboardService->get14gazetted()}}</span></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div><!-- card-body -->
        </div><!-- card -->
      </div>
