  @if(Auth::User()->role_code=='DI1570279796'|| Auth::User()->role_code=='BI1570278454' || Auth::User()->role_code=='AS1570278425')
<div class="col-md-4 mg-t-10">
        <div class="card">
          <div class="card-header pd-b-0 pd-t-20 bd-b-0">
            <h6 class="mg-b-0">Gazette Publications</h6>
          </div><!-- card-header -->
          <div class="card-body pd-y-10">
      {{--       <div class="d-flex align-items-baseline tx-rubik">
              <h1 class="tx-40 lh-1 tx-normal tx-spacing--2 mg-b-5 mg-r-5">9.8</h1>
              <p class="tx-11 tx-color-03 mg-b-0"><span class="tx-medium tx-success">1.6% <i class="icon ion-md-arrow-up"></i></span></p>
            </div> --}}
           {{--  <h6 class="tx-uppercase tx-spacing-1 tx-semibold tx-10 tx-color-02 mg-b-15">Performance Score</h6> --}}
            <div class="progress bg-transparent op-7 ht-10 mg-b-15">
              <div class="progress-bar bg-primary wd-50p" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
              <div class="progress-bar bg-success wd-30p bd-l bd-white" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
              <div class="progress-bar bg-warning wd-5p bd-l bd-white" role="progressbar" aria-valuenow="5" aria-valuemin="0" aria-valuemax="100"></div>
              <div class="progress-bar bg-pink wd-15p bd-l bd-white" role="progressbar" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
            {{--   <div class="progress-bar bg-teal wd-10p bd-l bd-white" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
              <div class="progress-bar bg-purple wd-5p bd-l bd-white" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div> --}}
            </div>

            <table class="table-dashboard-two">
              <tbody>
                <tr>
                  <td><div class="wd-12 ht-12 rounded-circle bd bd-3 bd-primary"></div></td>
                  <td class="tx-medium">12<sup>th </sup> Sentence</td>
                  <td class="text-right">3,007</td>
                  <td class="text-right">50%</td>
                </tr>
                <tr>
                  <td><div class="wd-12 ht-12 rounded-circle bd bd-3 bd-success"></div></td>
                  <td class="tx-medium">14<sup>th </sup> Sentence</td>
                  <td class="text-right">1,674</td>
                  <td class="text-right">30%</td>
                </tr>
                <tr>
                  <td><div class="wd-12 ht-12 rounded-circle bd bd-3 bd-warning"></div></td>
                  <td class="tx-medium">55<sup>th </sup> Sentence</td>
                  <td class="text-right">125</td>
                  <td class="text-right">5%</td>
                </tr>
                <tr>
                  <td><div class="wd-12 ht-12 rounded-circle bd bd-3 bd-pink"></div></td>
                  <td class="tx-medium">Amendments Form</td>
                  <td class="text-right">98</td>
                  <td class="text-right">15%</td>
                </tr>
               {{--  <tr>
                  <td><div class="wd-12 ht-12 rounded-circle bd bd-3 bd-teal"></div></td>
                  <td class="tx-medium">Poor</td>
                  <td class="text-right">512</td>
                  <td class="text-right">10%</td>
                </tr>
                <tr>
                  <td><div class="wd-12 ht-12 rounded-circle bd bd-3 bd-purple"></div></td>
                  <td class="tx-medium">Very Poor</td>
                  <td class="text-right">81</td>
                  <td class="text-right">4%</td>
                </tr> --}}
              </tbody>
            </table>
          </div><!-- card-body -->
        </div><!-- card -->
      </div>
      @endif