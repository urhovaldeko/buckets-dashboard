<?php

date_default_timezone_set('Europe/Tallinn');

# This is your budget file
$budget = "/Users/urhovaldeko/Nextcloud/Documents/Buckets/Budget.buckets";

# Buckets that have the same amount at the start of each month
$monthly_buc = array(6, 7, 14, 2);

# Buckets that grow
$accumul_buc = array(18);

# Buckets with saving goal
$savings_buc = array(15, 16, 54);

$curr_pre  = '';
$curr_post = ' €';
$order = 'id asc';

$monthly_ids = join("','", $monthly_buc);
$accumul_ids = join("','", $accumul_buc);
$savings_ids = join("','", $savings_buc);

$db = new SQLite3($budget);
$monthly_res = $db->query("select name, (goal-balance)/100.0 as spent, balance/100.0 as available from bucket where id in ('$monthly_ids')
                          order by $order");
$accumul_res = $db->query("select name, abs(sum(bucket_transaction.amount)/100.0) as spent, balance/100.0 as available from bucket 
                          inner join bucket_transaction on bucket_transaction.bucket_id = bucket.id where bucket.id in ('$accumul_ids') and 
                          bucket_transaction.amount < 0 and bucket_transaction.posted like '" . date('Y-m') . "-%' order by bucket.$order");
$savings_res = $db->query("select name, balance/100.0 as balance, (goal-balance)/100.0 as to_go from bucket where id in ('$savings_ids') order by $order");
$in_bank = $db->querySingle("select sum(balance)/100.0 from account");
$spent = $db->querySingle("select abs(sum(amount))/100.0 from account_transaction where posted like '" . date('Y-m') . "-%' and general_cat=''");

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>Buckets Dashboard</title>
    <link href="/static/bootstrap.min.css" rel="stylesheet">
    <link href="/static/style.css" rel="stylesheet">
  </head>
  <body>

<div class="container py-2">

  <header>
    <div class="pricing-header p-3 pb-md-4 mx-auto text-center">
      <h1 class="display-5 fw-normal">Buckets Dashboard</h1>
      <p class="fs-5 text-muted">Last updated <?php echo date("Y-m-d H:i:s"); ?></p>
    </div>
  </header>

  <main>
    <div class="row row-cols-1 row-cols-md-3 mb-3 text-center">

      <div class="col">
        <div class="card mb-4 rounded-3 shadow-sm border-success">
          <div class="card-header py-2 text-white bg-success border-success">
            <h4 class="my-0 fw-normal">Assets</h4>
          </div>
          <div class="card-body">
            <h1 class="card-title pricing-card-title"><?php echo $curr_pre; echo number_format($in_bank, 2, '.', ' '); echo $curr_post; ?></h1>
            <ul class="list-unstyled mt-3 mb-4">
              <li class="text-muted fw-light"><?php echo $curr_pre; echo number_format($spent, 2, '.', ' ');  
                echo $curr_post; ?> spent in <?php echo date("F"); ?></li>
            </ul>
          </div>
        </div>
      </div>

<?php while ($row = $monthly_res->fetchArray()) { ?>
      <div class="col">
        <div class="card mb-4 rounded-3 shadow-sm border-primary">
          <div class="card-header py-2 text-white bg-primary border-primary">
            <h4 class="my-0 fw-normal"><?php echo $row['name']; ?></h4>
          </div>
          <div class="card-body">
            <h1 class="card-title pricing-card-title"><?php echo $curr_pre; echo number_format($row['available'], 2, '.', ' ');  echo $curr_post; ?></h1>
            <ul class="list-unstyled mt-3 mb-4">
              <li class="text-muted fw-light"><?php echo $curr_pre; echo number_format($row['spent'], 2, '.', ' ');  echo $curr_post; ?> spent</li>
            </ul>
          </div>
        </div>
      </div>
<?php } ?>

<?php while ($row = $accumul_res->fetchArray()) { ?>
      <div class="col">
        <div class="card mb-4 rounded-3 shadow-sm border-info">
          <div class="card-header py-2 text-white bg-info border-info">
            <h4 class="my-0 fw-normal"><?php echo $row['name']; ?></h4>
          </div>
          <div class="card-body">
            <h1 class="card-title pricing-card-title"><?php echo $curr_pre; echo number_format($row['available'], 2, '.', ' ');  echo $curr_post; ?></h1>
            <ul class="list-unstyled mt-3 mb-4">
              <li class="text-muted fw-light"><?php echo $curr_pre; echo number_format($row['spent'], 2, '.', ' ');  echo $curr_post; ?> spent</li>
            </ul>
          </div>
        </div>
      </div>
<?php } ?>

<?php while ($row = $savings_res->fetchArray()) { ?>
      <div class="col">
        <div class="card mb-4 rounded-3 shadow-sm border-warning">
          <div class="card-header py-2 text-white bg-warning border-warning">
            <h4 class="my-0 fw-normal"><?php echo $row['name']; ?></h4>
          </div>
          <div class="card-body">
            <h1 class="card-title pricing-card-title"><?php echo $curr_pre; echo number_format($row['balance'], 2, '.', ' ');  echo $curr_post; ?></h1>
            <ul class="list-unstyled mt-3 mb-4">
              <li class="text-muted fw-light"><?php echo $curr_pre; echo number_format($row['to_go'], 2, '.', ' ');  echo $curr_post; ?> to go</li>
            </ul>
          </div>
        </div>
      </div>
<?php } ?>

    </div>

  </main>
</div>
</body>
</html>