<?php
/**
 * User: zjz
 * File: view_compile.php
 * Date: 2018/12/12
 * Time: 13:13
 */
function compile($view,$types,$load_tag){
    //  get view content
    $views = file_get_contents($view);

    //  compile if
    $views = preg_replace_callback('/@if([^@{\?]*)[)]/',function ($v){
        return '<?php if'.$v[1].'){ ?>';
    },$views);
    //  compile @elseif
    $views = preg_replace_callback('/@el([^@{\?]*)[)]/',function ($v){return '<?php }elseif'.$v[1].'){ ?>';},$views);
    //  compile @else
    $views = preg_replace_callback('/@else/',function ($v){return '<?php }else{ ?>';},$views);
    //  compile endif
    $views = preg_replace_callback('/@endif/',function ($v){return '<?php }; ?>';},$views);

    // compile foreach
    $views = preg_replace_callback('/@foreach([^@{\?]*)[)]/',function ($v){return '<?php foreach'.$v[1].'){ ?>';},$views);
    // compile endforeach
    $views = preg_replace_callback('/@endforeach/',function ($v){return '<?php }; ?>';},$views);

    // compile for
    $views = preg_replace_callback('/@for([^@{\?]*)[)]/',function ($v){
        return '<?php for'.$v[1].'){ ?>';
    },$views);
    // compile endfor
    $views = preg_replace_callback('/@endfor/',function ($v){return '<?php }; ?>';},$views);

    // compile @{{ }}
    $views = preg_replace_callback('/@{{([^@\$\?]*)}}/',function ($v){return '{{@'.$v[1].'}}';},$views);

    // compile {{ }}
    $views = preg_replace_callback('/{{([^@{\?]*)}}/',function ($v){return '<?php echo '.$v[1].'; ?>';},$views);

    // continue to compile @{{ }}
    $views = preg_replace_callback('/{{@(.*)}}/',function ($v){return '{{'.$v[1].'}}';},$views);

    // compile extends
    $views = preg_replace_callback('/@extends([^@{\?]*)[)]/',function ($v){return '<?php extends'.$v[1].'); ?>';},$views);

    // compile @json
    $views = preg_replace_callback('/@json([^@{\?]*)[)]/',function ($v){return '<?php echo json_encode'.$v[1].'); ?>';},$views);

    // compile @raw{ }
    $views = preg_replace_callback('/@raw{([^@{\?]*)}/m',function ($v){return '<?php '.$v[1].' ?>';},$views);

    // load auto_load.json
    $auto_load_source = file_get_contents(App.'static/auto_load.json');
    $auto_load_source = json_decode($auto_load_source,true);
    $load_str = '';
    foreach ($auto_load_source as $k => $v){
        if (preg_match('/'.$k.'\..*/',$load_tag)||$k==$load_tag){
            foreach ($v['css'] as $css){
                $load_str.='    <link rel="stylesheet" type="text/css" href="'.$css.'">'."\n";
            }
            foreach ($v['js'] as $js){
                $load_str.='    <script type="text/javascript" src="'.$js.'"></script>'."\n";
            }
        }
    }
    $GLOBALS['load_str'] = $load_str;

    // add css, js link tag
    $views = preg_replace_callback('/<!--[\s]*tiny_auto[\s]*-->/',function ($v){return $GLOBALS['load_str'];},$views);

    // add shortcut icon
    $views = preg_replace('/<\/head>/','<link id="favicon" rel="shortcut icon" type="image/x-icon" href="/favicon.ico">'."\n".'</head>',$views);

    $view_name = explode('/',$view);
    if ($types == 'single'){
        $file_name = view_cache_path().sha1($view_name[count($view_name)-1]).'.php';
    }else{
        $file_name = view_cache_path().$view_name[count($view_name)-2].'/'.sha1($view_name[count($view_name)-1]).'.php';
        if (!is_dir(view_cache_path().$view_name[count($view_name)-2])){
            mkdir(view_cache_path().$view_name[count($view_name)-2]);
        }
    }
    $vie = fopen($file_name,'w+');
    $data_compile =
'<?php
 use src\tiny\DB\db;
 use src\tiny\Logs\Error_log;
 use function src\tiny\route\get_action;
 use src\tiny\route\route;
 use function src\tiny\route\route_exits;
 use src\tiny\route\Request;
 use src\tiny\route\route_load;
 use function src\tiny\safe\tiny_bad;
 foreach($GLOBALS["data"] as $k => $v){
     $$k = $v;
 } 
?>
';
    fwrite($vie,$data_compile);
    fwrite($vie,$views);
    fclose($vie);
    require_once App.'src/tiny/view/view_render.php';
    render($file_name);
}
