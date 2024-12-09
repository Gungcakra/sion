<?php
session_start();

require_once "../../library/konfigurasi.php";

//CEK USER
checkUserSession($db);


?>

<div class="d-flex shadow p-2 w-100 bg-white">
  <!-- <form class="d-none d-sm-inline-block form-inline ml-md-3 my-2 my-md-0 mw-100 navbar-search border">
    <div class="input-group">
      <input type="text" class="form-control bg-light border-0 small" placeholder="Select date range..." aria-label="DateRange" id="rentang" autocomplete="off" />
      <div class="input-group-append">
        <a class="btn btn-primary">
          <i class="fa-solid fa-calendar-days"></i>
        </a>
      </div>
    </div>
  </form> -->
  <form id="revenueForm">
    <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-success m-1 shadow-sm ml-2 p-2" onclick="exportExcel()">
      <i class="fa-solid fa-file-excel"></i> Download Report
    </button>
  </form>


  <!-- <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm ml-auto" data-toggle="modal" data-target="#employeeModal">
                            <i class="fas fa-plus fa-sm text-white"></i> Add Employee
                        </button> -->
</div>

<div class="w-100" id="daftarRevenue">

</div>
<script>
  $(document).ready(function() {
    $('#rentang').daterangepicker({
      opens: 'left',
      autoUpdateInput: false,
      locale: {
        cancelLabel: 'Clear',
        format: "YYYY-MM-DD"
      },
      buttonClasses: " btn",
      applyClass: "btn-primary",
      cancelClass: "btn-secondary",

    });


    // Apply button event
    $('#rentang').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));

      cariDaftarReservation();
    });

    $('#rentang').on('cancel.daterangepicker', function(ev, picker) {
      $(this).val('');
    });
  });
</script>