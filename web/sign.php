<?php

require '../vendor/autoload.php';

$config = require '../config/config.php';

$id = Ramsey\Uuid\Uuid::uuid4()->toString();

$pdf = new TCPDF();
$pdf->AddPage();
$pdf->writeHTML('<pre>' . htmlspecialchars(print_r($_POST, true)) . '</pre>');
$pdf->output(realpath('../data') . '/' . $id . '.pdf', 'F');


$client = new Onnistuu_External_Client($config['clientId'], $config['secret']);

$encrypted = $client->encryptRequest(array(
    'stamp' => 'Sopimus ' . date('r'),
    'return_success' => $config['location'] . '/return.php',
    'document' => $config['location'] . '/document.php?id=' . $id,
    'requirements' => array(
        array(
            'type' => 'person',
            'identifier' => $_POST['identifier'],
        ),
    ),
));

?>

<form method="POST" action="https://www.onnistuu.fi/external/entry/">
    <input type="hidden" name="return_failure" value="<?php echo $config['location'] . '/error.php'; ?>" />
    <input type="hidden" name="customer" value="<?php echo $config['clientId']; ?>" />
    <input type="hidden" name="data" value="<?php echo $encrypted['data']; ?>" />
    <input type="hidden" name="iv" value="<?php echo $encrypted['iv']; ?>" />
    <button type="submit">Jatka</button>
</form>
<script>
document.querySelector('form').submit();
</script>

