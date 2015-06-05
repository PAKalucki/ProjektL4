<?php
include_once("Service/serviceClasses.php");
class Controller
{
    private $message;
    
    public function __construct()
    {
    	if (isset($_GET['type']) && isset($_GET['ret'])) {
            $this->message = array(
        		'type' => $_GET['type'],
        		'msg' => base64_decode($_GET['ret'])
            );
    	}
    }

    public function run()
    {
    	try {
            $model = isset($_GET['url']) ? $_GET['url'] : 'main';
            $path = 'model/' . $model . '.php';
            $model .= "_Model";
            
            if (is_file($path)) {
                include_once ($path);
                $this->model = new $model();
            } else
                throw new RuntimeException('Nie można otworzyć pliku modelu: ' . $path);
    	} catch (Exception $e) {
    		$this->handleError($e);
    	}
    }

    public function handleError($e)
    {
    	ob_clean();
    	
        $str = $e->getMessage() . '<br><br>
            File: ' . $e->getFile() . '<br>
            Code line: ' . $e->getLine() . '<br>
            <pre>Trace: <br>' . $e->getTraceAsString() . '</pre>';
        
        $content = file_get_contents("view/exception.phtml");
        if (!$content) {
        	echo ($e->getMessage() . '<br>
            W pliku: ' . $e->getFile() . '<br>
            Linia: ' . $e->getLine() . '<br>
            <pre>Trace: <br>' . $e->getTraceAsString() . '</pre>');
        } else {
        	echo (str_replace("{message}", $str, $content));
        }
        
        exit();
    }

    public function redirect($url, $type = "", $ret = "")
    {
        if ($type != "" && $ret != "")
            if (strpos($url, '?') !== false)
                $url .= '&type=' . $type . '&ret=' . base64_encode($ret);
            else
                $url .= '?type=' . $type . '&ret=' . base64_encode($ret);
        
        if (headers_sent()) {
            echo ('<meta http-equiv="refresh" content="0; URL=' . $url . '">');
        } else
            header('location: ' . $url);
        
        exit();
    }

    public function getMessage()
    {
        if ($this->message) {
            echo '
				<table style="width: 500px;" cellpadding=4 cellspacing=1 align=center>
					<tr bgcolor=#505050>
						<td style="color:#f1e0c6; colspan=2"><b>Powiadomienie:</b></td>
					</tr>
					<tr bgcolor=#D4C0A1>
						<td colspan=2>' . $this->message['msg'] . '</td>
					</tr>
				</table><br>
			';
        }
    }
    
    public function setMessage($type, $msg)
    {
    	$this->message = array(
			'type' => $type,
			'msg' => $msg
    	);
    }

    public function getAction()
    {
        return (isset($_GET['page']) ? $_GET['page'] : '');
    }

    public function action($str)
    {
        if ($str)
            echo "index.php?url=" . $_GET['url'] . "&page=" . $str;
        else
            echo "index.php?url=" . $_GET['url'];
    }
    
    public function generateUrl(array $params = array())
    {
        $base = 'index.php';
        if ($params) {
            $base .= '?' . http_build_query($params);
        }
        
        return $base;
    }
}
?>