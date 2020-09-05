<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <?php $this->load->view('common_css'); ?>
  <title></title>
</head>

<body>
  <div class="container-fluid">
    <br/>
    <?php echo $this->system->terms_condition; ?>
  </div>
  <?php $this->load->view('common_js'); ?>
</body>

</html>