<form method="GET" class="container mt-3 mb-3">
  <div class="row g-3 align-items-center">
    <div class="col-auto">
      <label for="from_date" class="col-form-label">From:</label>
    </div>
    <div class="col-auto">
      <input type="date" id="from_date" name="from_date" class="form-control" value="<?= isset($_GET['from_date']) ? $_GET['from_date'] : '' ?>">
    </div>
    <div class="col-auto">
      <label for="to_date" class="col-form-label">To:</label>
    </div>
    <div class="col-auto">
      <input type="date" id="to_date" name="to_date" class="form-control" value="<?= isset($_GET['to_date']) ? $_GET['to_date'] : '' ?>">
    </div>
    <div class="col-auto">
      <button type="submit" class="btn btn-primary">Filter</button>
    </div>
  </div>
</form>
