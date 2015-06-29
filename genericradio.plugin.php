<?php
/*
@name Relais Radio SCS/Chacon
@author Rémi SARRAILH <maditnerd@gmail.com>
@link http://maditnerd.github.io/genericRadio/
@licence cc-by-nc-sa
@version 1.1
@description Allumer et éteindre des prises radio 433 SCS/Pheonix (RcSwitch)  et Chacon (HomeEasy) 
*/

//On appelle les entités de base de données
require_once(dirname(__FILE__).'/GenericRadio.class.php');

//Cette fonction ajoute une commande vocale
function genericradio_plugin_vocal_command(&$response, $actionUrl)
{
    global $conf;

    $genericRadioManager = new GenericRadio();

    $genericRadios = $genericRadioManager->populate();
    foreach ($genericRadios as $genericRadio) {
        if (!empty($genericRadio->onCommand)) {
            $response['commands'][] = array(
                'command'=>$conf->get('VOCAL_ENTITY_NAME').', '.$genericRadio->onCommand,
                'url'=>$actionUrl.'?action=genericRadio_vocal_change_state&engine='.$genericRadio->id.'&state=1',
                'confidence'=>('0.90'+$conf->get('VOCAL_SENSITIVITY')));
        }
        if (!empty($genericRadio->offCommand)) {
            $response['commands'][] = array(
                'command'=>$conf->get('VOCAL_ENTITY_NAME').', '.$genericRadio->offCommand,
                'url'=>$actionUrl.'?action=genericRadio_vocal_change_state&engine='.$genericRadio->id.'&state=0',
                'confidence'=>('0.90'+$conf->get('VOCAL_SENSITIVITY')));
        }
    }
}

//cette fonction comprends toutes les actions du plugin qui ne nécessitent pas de vue html
function genericradio_plugin_action()
{
    global $_,$conf,$myUser;

    //Action de réponse à la commande vocale "Yana, commande de test"
    switch($_['action']){

        //Save ID
        case 'genericRadio_save_genericRadio':
            Action::write(
                function ($_, &$response) {
                    $genericRadioManager = new GenericRadio();

                    if (empty($_['nameGenericRadio'])) {
                        throw new Exception("Le nom est obligatoire");
                    }
                   // if (!is_numeric($_['radioCodeGenericRadio'])) {
                   //     throw new Exception("Le code radio est obligatoire et doit être numerique");
                   // }

                    $genericRadio = !empty($_['id']) ? $genericRadioManager->getById($_['id']): new GenericRadio();
                    $genericRadio->name = $_['nameGenericRadio'];
                    $genericRadio->description = $_['descriptionGenericRadio'];
                    $genericRadio->room = $_['roomGenericRadio'];
                    $genericRadio->onCommand = $_['onGenericRadio'];
                    $genericRadio->offCommand = $_['offGenericRadio'];
                    $genericRadio->icon = $_['iconGenericRadio'];
                    $genericRadio->radiocodeOn = $_['radioCodeGenericRadioOn'];
                    $genericRadio->radiocodeOff = $_['radioCodeGenericRadioOff'];
                    $genericRadio->save();
                    $response['message'] = 'Relais enregistré avec succès';
                },
                array('plugin_genericradio'=>'c')
            );
            break;

        case 'genericRadio_import_genericRadio':
            Action::write(
                function ($_, &$response) {
                    $exportRadios = json_decode($_POST["import"]);
                    //var_dump($exportRadios);
                    if ($exportRadios) {
                          $roomManager = new Room();
                          $nbRoom = $roomManager->rowCount();
                          //var_dump($nbRoom);
                          
                        foreach ($exportRadios as $exportRadio) {
                                 $genericRadio = new GenericRadio();
                                 $genericRadio->name = $exportRadio->name;
                                 $genericRadio->description = $exportRadio->description;
                            if ($exportRadio->room <= $nbRoom) {
                                    $genericRadio->room = $exportRadio->room;
                            } else {
                                    $genericRadio->room = 1;
                            }

                                 $genericRadio->onCommand = $exportRadio->onCommand;
                                 $genericRadio->offCommand = $exportRadio->offCommand;
                                 $genericRadio->icon = $exportRadio->icon;
                                 $genericRadio->radiocodeOn = $exportRadio->radiocodeOn;
                                 $genericRadio->radiocodeOff = $exportRadio->radiocodeOff;
                                 $genericRadio->save();
                                 $response['message'] = 'Relais importés avec succès';
                        }
                    } else {
                        throw new Exception("Les valeurs importés sont incorrectes, vérifier les dans un lecteur JSON");
                    }
                },
                array('plugin_genericradio'=>'c')
            );
            break;

        //Delete ID
        case 'genericRadio_delete_genericRadio':
            Action::write(
                function ($_, $response) {
                    $genericRadioManager = new GenericRadio();
                    $genericRadioManager->delete(array('id'=>$_['id']));
                },
                array('plugin_genericradio'=>'d')
            );
            break;


        //Save settings
        case 'genericRadio_plugin_setting':
            Action::write(
                function ($_, &$response) {
                    global $conf;
                    $conf->put('plugin_genericRadio_emitter_pin', $_['emiterPin']);
                    $conf->put('plugin_genericRadio_receiver_pin', $_['receiverPin']);
                    $response['message'] = 'Configuration enregistrée';
                },
                array('plugin_genericradio'=>'c')
            );
            break;

        case 'genericRadio_manual_change_state':
            Action::write(
                function ($_, &$response) {
                    $error = genericradio_plugin_change_state($_['engine'], $_['state']);
                    if ($error) {
                        $response['errors'][] = $error;
                    }
                },
                array('plugin_genericradio'=>'c')
            );
            break;

        case 'genericRadio_vocal_change_state':
            global $_,$myUser;
            try {
                $response['responses'][0]['type'] = 'talk';
                if (!$myUser->can('plugin_genericradio', 'u')) {
                    throw new Exception(
                        'Je ne vous connais pas, ou alors vous n\'avez pas le droit, je refuse de faire ça!'
                    );
                }
                genericradio_plugin_change_state($_['engine'], $_['state']);
                $response['responses'][0]['sentence'] = Personality::response('ORDER_CONFIRMATION');
            } catch (Exception $e) {
                $response['responses'][0]['sentence'] = Personality::response('WORRY_EMOTION').'! '.$e->getMessage();
            }
            $json = json_encode($response);
            echo ($json=='[]'?'{}':$json);
            break;

        case 'genericRadio_plugin_setting':
            Action::write(
                function ($_, &$response) {
                    global $conf;
                    $conf->put('plugin_genericRadio_emitter_pin', $_['emiterPin']);
                    $conf->put('plugin_genericRadio_receiver_pin', $_['receiverPin']);
                    $response['message'] = 'Configuration modifiée avec succès';
                },
                array('plugin_genericradio'=>'u')
            );
            break;

        case 'genericRadio_load_widget':

            require_once(dirname(__FILE__).'/../dashboard/Widget.class.php');

            Action::write(
                function ($_, &$response) {
                    $widget = new Widget();
                    $widget = $widget->getById($_['id']);
                    $data = $widget->data();

                    $content = '';

                    if (empty($data['relay'])) {
                        $content = 'Choisissez un relais en cliquant sur l \'icone 
                                <i class="fa fa-wrench"></i> de la barre du widget';
                    } else {
                        if (fileperms(Plugin::path().'RCsend')!='36333') {
                            $content .= '<div style="margin:0px;" class="flatBloc pink-color">
                            Attention, les droits vers le fichier <br/> RCsend sont mal réglés.<br/> 
                            Référez vous à <span style="cursor:pointer;text-decoration:underline;" 
                            onclick="window.location.href=\'http://maditnerd.github.io/genericRadio/\';">
                            la doc</span> pour les régler</div>';
                        }
                            $relay = new GenericRadio();
                            $relay = $relay->getById($data['relay']);

                            $response['title'] = $relay->name;

                            $content .= '
                            <!-- CSS -->
                            <style>

                               .genericradio_relay_pane {
                                background: none repeat scroll 0 0 #50597b;
                                list-style-type: none;
                                margin: 0;
                                cursor:default;
                                width: 100%;
                            }
                            .genericradio_relay_pane li {
                                background: none repeat scroll 0 0 #50597b;
                                display: inline-block;
                                margin: 0 1px 0 0;
                                padding: 10px;
                                cursor:default;
                                vertical-align: top;
                            }
                            .genericradio_relay_pane li h2 {
                                color: #ffffff;
                                font-size: 16px;
                                margin: 0 0 5px;
                                padding: 0;
                                cursor:default;
                            }
                            .genericradio_relay_pane li h1 {
                                color: #B6BED9;
                                font-size: 14px;
                                margin: 0 0 10px;
                                padding: 0;
                                cursor:default;
                            }

                            .genericradio_relay_pane li.genericradio-case{
                                background-color:  #373f59;
                                width: 55px;
                                cursor:pointer;
                            }
                            .genericradio-case i{
                                color:#8b95b8;
                                font-size:50px;
                                transition: all 0.2s ease-in-out;
                            }
                            .genericradio-case.active i{
                                color:#ffffff;
                                text-shadow: 0 0 10px #ffffff;
                            }

                            .genericradio-case.active i.fa-lightbulb-o{
                                color:#FFED00;
                                text-shadow: 0 0 10px #ffdc00;
                            }
                            .genericradio-case.active i.fa-power-off{
                                color:#BDFF00;
                                text-shadow: 0 0 10px #4fff00;
                            }

                            .genericradio-case.active i.fa-flash{
                                color:#FFFFFF;
                                text-shadow: 0 0 10px #00FFD9;
                            }

                            .genericradio-case.active i.fa-gears{
                                color:#FFFFFF;
                                text-shadow: 0 0 10px #FF00E4;
                            }

                        </style>

                        <!-- CSS -->
                        <ul class="genericradio_relay_pane">
                            <li class="genericradio-case '.($relay->state?'active':'').'" 
                                onclick="plugin_genericradio_change(this,'.$relay->id.');" style="text-align:center;">
                                <i title="On/Off" class="'.$relay->icon.'"></i>
                            </li>
                            <li>
                                <h2>'.$relay->description.'</h2>
                                <h1>CODE ON '.$relay->radiocodeOn.'</h1>
                                  <h1>CODE OFF '.$relay->radiocodeOff.'</h1>
                            </li>
                        </ul>

                        <!-- JS -->
                        <script type="text/javascript">
                            function plugin_genericradio_change(element,id){
                                var state = $(element).hasClass(\'active\') ? 0 : 1 ;

                                $.action(
                                {
                                    action : \'genericRadio_manual_change_state\', 
                                    engine: id,
                                    state: state
                                },
                                function(response){
                                    $(element).toggleClass("active");
                                }
                                );

}
</script>
';
                    }
                    $response['content'] = $content;
                }
            );
            break;

        case 'genericRadio_edit_widget':
            require_once(dirname(__FILE__).'/../dashboard/Widget.class.php');
            $widget = new Widget();
            $widget = $widget->getById($_['id']);
            $data = $widget->data();

            $relayManager = new GenericRadio();
            $relays = $relayManager->populate();

            $content = '<h3>Relais ciblé</h3>';

            if (count($relays) == 0) {
                $content = 'Aucun relais existant dans yana, 
                <a href="setting.php?section=genericRadio">Créer un relais ?</a>';
            } else {
                $content .= '<select id="relay">';
                $content .= '<option value="">-</option>';
                foreach ($relays as $relay) {
                    $content .= '<option value="'.$relay->id.'">'.$relay->name.'</option>';
                }
                $content .= '</select>';
            }
            echo $content;
            break;

        case 'genericRadio_save_widget':
                require_once(dirname(__FILE__).'/../dashboard/Widget.class.php');
                $widget = new Widget();
                $widget = $widget->getById($_['id']);
                $data = $widget->data();

                $data['relay'] = $_['relay'];
                $widget->data($data);
                $widget->save();
                echo $content;
            break;

        case 'genericRadio_rcswitchdetect':
            if ($conf->get('plugin_genericRadio_receiver_pin') == "") {
                        $conf->put('plugin_genericRadio_receiver_pin', 7);
            }
            $cmd = dirname(__FILE__).'/RCreceive '.$conf->get('plugin_genericRadio_receiver_pin');
            $answer = system($cmd, $out);
            if ($answer != "Wrong GPIO" && $out == 1) {
                        echo "Check Permissions";
            }
            break;

        case 'genericRadio_chacondetect':
            if ($conf->get('plugin_genericRadio_receiver_pin') == "") {
                        $conf->put('plugin_genericRadio_receiver_pin', 7);
            }
            $cmd = dirname(__FILE__).'/HEreceive '.$conf->get('plugin_genericRadio_receiver_pin');
            $answer = system($cmd, $out);
            if ($answer != "Wrong GPIO" && $out == 1) {
                        echo "Check Permissions";
            }
            break;
    }
}

function genericradio_plugin_change_state($engine, $state)
{
    global $conf;
    $genericRadio = new GenericRadio();
    $genericRadio = $genericRadio->getById($engine);

    if ($state) {
        $code = $genericRadio->radiocodeOn;
    } else {
        $code = $genericRadio->radiocodeOff;
    }

    //If emitter not defined put it to default
    if ($conf->get('plugin_genericRadio_emitter_pin') == "") {
        $conf->put('plugin_genericRadio_emitter_pin', 0);
    }
    $cmd = dirname(__FILE__).'/RCsend '.$conf->get('plugin_genericRadio_emitter_pin')." ".$code;
    $genericRadio->state = $state;
    Functions::log('Launch system command : '.$cmd);
    exec($cmd, $out, $err);

    if ($err == 0) {
       // $error = implode(" ", $out);
    } else {
        if ($err == 126) {
            $error = "Yana n'a pas le droit d'exécuter la commande";
        } else {
            $error = $code." n'est pas un code correcte";
        }
    }


    $genericRadio->save();

    return $error;
}

function genericRadio_plugin_setting_page()
{
    global $_,$myUser,$conf;
    if (isset($_['section']) && $_['section']== 'genericRadio') {
        if (!$myUser) {
            throw new Exception('Vous devez être connecté pour effectuer cette action');
        }
        $genericRadioManager = new GenericRadio();
        $genericRadios = $genericRadioManager->populate();
        $roomManager = new Room();
        $rooms = $roomManager->populate();
        $selected =  new GenericRadio();
        
        $selected->icon = 'fa fa-flash';


        //Si on est en mode modification
        if (isset($_['id'])) {
            $selected = $genericRadioManager->getById($_['id']);
        }

        $icons = array(
            'fa fa-lightbulb-o',
            'fa fa-power-off',
            'fa fa-flash',
            'fa fa-gears',
            'fa fa-align-justify',
            'fa fa-adjust',
            'fa fa-arrow-circle-o-right',
            'fa fa-desktop',
            'fa fa-music',
            'fa fa-bell-o',
            'fa fa-beer',
            'fa fa-bullseye',
            'fa fa-automobile',
            'fa fa-book',
            'fa fa-bomb',
            'fa fa-clock-o',
            'fa fa-cutlery',
            'fa fa-microphone',
            'fa fa-tint'
            );
            ?>

            <div class="span9 userBloc">

                <h1>Relais</h1>
                <p>Gestion des relais radios (SCS/CHACON) <a class="btn btn-warning" href="setting.php?section=preference&block=genericRadio">Electronique</a></p>  

                <fieldset>
                    <legend>Ajouter/Modifier un relais radio</legend>

                    <div class="left">

                        <label for="nameGenericRadio">Nom</label>
                        <input type="hidden" id="id" value="<?php echo $selected->id; ?>">
                        <input type="text" id="nameGenericRadio" value="<?php echo $selected->name; ?>" placeholder="Lumiere Canapé…"/>
                        
                        <label for="descriptionGenericRadio">Description</label>
                        <input type="text"  value="<?php echo $selected->description; ?>" id="descriptionGenericRadio" placeholder="Relais sous le canapé…" />

                        <label for="iconGenericRadio">Icone</label>
                        <input type="hidden"  value="<?php echo $selected->icon; ?>" id="iconGenericRadio"  />
                        
                        <div>
                            <div style='margin:5px;'>
                                <?php foreach ($icons as $i => $icon) {
                                    if ($i%6==0) {
                                        echo '</div><div style="margin:5px;">';
                                    }
                                    ?>
                                    <i style="width:25px;" onclick="plugin_genericradio_set_icon(this,'<?php echo $icon; ?>');" 
                                    class="<?php echo $icon; ?> btn <?php echo $selected->icon==$icon?'btn-success':''; ?>"
                                    >
                                    </i>
                                    <?php
}
                                    ?> 
                                </div>
                            </div>

                            <label for="radioCodeGenericRadioOn">Code radio ON</label>
                            <input type="text" value="<?php echo $selected->radiocodeOn; ?>" name="radioCodeGenericRadioOn" id="radioCodeGenericRadioOn" placeholder="1:1234" />
                            <div class="input-append">
                                <span onclick="plugin_genericradio_detectcode(this,'radioCodeGenericRadioOn','rcswitch')" class="btn">SCAN RCSwitch</span>
                                <span onclick="plugin_genericradio_detectcode(this,'radioCodeGenericRadioOn','chacon')" class="btn">SCAN Chacon</span>
                            </div>
                            <label for="radioCodeGenericRadioOff">Code radio OFF</label>
                            <input type="text" value="<?php echo $selected->radiocodeOff; ?>" name="radioCodeGenericRadioOff" id="radioCodeGenericRadioOff" placeholder="1:1234" />
                            <div class="input-append">
                                <span onclick="plugin_genericradio_detectcode(this,'radioCodeGenericRadioOff','rcswitch')" class="btn">SCAN RCSwitch</span>
                                <span onclick="plugin_genericradio_detectcode(this,'radioCodeGenericRadioOff','chacon')" class="btn">SCAN Chacon</span>
                            </div>
                            <label for="onGenericRadio">Commande vocale "ON" associée</label>
                            <?php echo $conf->get('VOCAL_ENTITY_NAME') ?>, <input type="text" id="onGenericRadio" value="<?php echo $selected->onCommand; ?>" placeholder="Allume la lumière, Ouvre le volet…"/>

                            
                            <label for="offGenericRadio">Commande vocale "OFF" associée</label>
                            <?php echo $conf->get('VOCAL_ENTITY_NAME') ?>, <input type="text" id="offGenericRadio" value="<?php echo $selected->offCommand; ?>" placeholder="Eteinds la lumière, Ferme le volet…"/>
                            

                            <label for="roomGenericRadio">Pièce de la maison</label>
                            <select id="roomGenericRadio">
                                <?php foreach ($rooms as $room) {
                                    ?>
                                <option <?php if ($selected->room == $room->getId()) { echo "selected"; } ?> value="<?php echo $room->getId(); ?>">
                                <?php echo $room->getName(); ?>
                                </option>
                                <?php
} ?>
                            </select>
                        </div>

                        <div class="clear"></div>
                        <br/><button onclick="plugin_genericradio_save(this)" class="btn">Enregistrer</button>
                    </fieldset>
                    <br/>


                    <fieldset>
                        <legend>Consulter les relais radios existants</legend>
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Description</th>
                                    <th>Code ON</th>
                                    <th>Code OFF</th>

                                    <th>Pièce</th>                                   
                                </tr>
                            </thead>
                            
                            <?php foreach ($genericRadios as $genericRadio) {
                                $room = $roomManager->load(array('id'=>$genericRadio->room));
                                ?>
                                <tr>
                                    <td><?php echo $genericRadio->name; ?></td>
                                    <td><?php echo $genericRadio->description; ?></td>
                                    <td><?php echo $genericRadio->radiocodeOn; ?></td>
                                    <td><?php echo $genericRadio->radiocodeOff; ?></td>

                                    <td><?php echo $room->getName(); ?></td>
                                    <td>
                                        <a class="btn" href="setting.php?section=genericRadio&id=<?php echo $genericRadio->id; ?>">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        <div class="btn" onclick="plugin_genericradio_delete(<?php echo $genericRadio->id; ?>,this);">
                                            <i class="fa fa-times"></i>
                                        </div>
                                    </td>
                                </td>
                            </tr>
                            <?php
}
$exportRadios = $genericRadios;
foreach ($exportRadios as $key => $exportRadio) {
    unset($exportRadios[$key]->id);
    unset($exportRadios[$key]->state);
}
                            ?>
                        </table>
                    </fieldset>
               
                <legend>Exporter vos relais existants</legend>
                <textarea cols="100" ><?php echo json_encode($exportRadios); ?></textarea>
                <legend>Importer de nouveaux relais <a href="http://maditnerd.github.io/genericRadio">Liste des prises compatibles</a></legend>
                <div class="import">
                <textarea id="import" name="import" cols="100" ></textarea>
                
                        <button onclick="plugin_genericradio_import(this)" class="btn">Importer</button>
                </div>
                </div>

                <?php

                
    }
}

function genericRadio_plugin_setting_menu()
{
    global $_;
    echo '<li '.(isset($_['section']) && $_['section']=='genericRadio'?'class="active"':'').'>
    <a href="setting.php?section=genericRadio">
    <i class="fa fa-angle-right"></i> Relais radio (SCS/CHACON)
    </a>
    </li>';
}


function genericRadio_plugin_widget(&$widgets)
{
            $widgets[] = array(
                'uid'      => 'dash_genericradio',
                'icon'     => 'fa fa-bullseye',
                'label'    => 'Relais Radio (SCS/CHACON)',
                'background' => '#50597b',
                'color' => '#fffffff',
                'onLoad'   => 'action.php?action=genericRadio_load_widget',
                'onEdit'   => 'action.php?action=genericRadio_edit_widget',
                'onSave'   => 'action.php?action=genericRadio_save_widget',
                );
}




function genericRadio_plugin_preference_menu()
{
    global $_;
    echo '<li '.(@$_['block']=='genericRadio'?'class="active"':'').'><a  href="setting.php?section=preference&block=genericRadio"><i class="fa fa-angle-right"></i> Radio Relais (SCS/CHACON)</a></li>';
}

function genericRadio_plugin_preference_page()
{
    global $myUser,$_,$conf;
    if ((isset($_['section']) && $_['section']=='preference' && @$_['block']=='genericRadio' )) {
        if ($myUser!=false) {
?>

                    <?php
                    if (fileperms(Plugin::path().'RCsend')!='36333') {
                        ?>
                            <div class="flatBloc pink-color">
                                <b>RCsend</b> n'a pas les permissions pour envoyer des codes radios<br>
                                Tapez ceci dans un terminal pour résoudre ce problème:
                                <code>
                                    sudo chown root:www-data <?php echo  dirname(__FILE__)."/RCsend"  ?> &&<br>
                                    sudo chmod +sx  <?php echo  dirname(__FILE__)."/RCsend" ?>
                                </code>

                            </div>
                            <?php
                    } ?>

                            <?php  if (fileperms(Plugin::path().'RCreceive')!='36333') {
                                ?>
                                <div class="flatBloc pink-color">
                                    <b>RCreceive</b> n'a pas les permissions pour recevoir des codes radios RCSwitch<br>
                                    Tapez ceci dans un terminal pour résoudre ce problème:
                                    <code>
                                        sudo chown root:www-data <?php echo  dirname(__FILE__)."/RCreceive"  ?> &&<br>
                                        sudo chmod +sx  <?php echo  dirname(__FILE__)."/RCreceive" ?>
                                    </code>

                                </div>
                            <?php
} ?>
                            <?php  if (fileperms(Plugin::path().'HEreceive')!='36333') {
                                ?>
                                <div class="flatBloc pink-color">
                                    <b>HEreceive</b> n'a pas les permissions pour recevoir des codes radios Chacon<br>
                                    Tapez ceci dans un terminal pour résoudre ce problème:
                                    <code>
                                        sudo chown root:www-data <?php echo  dirname(__FILE__)."/HEreceive"  ?> &&<br>
                                        sudo chmod +sx  <?php echo  dirname(__FILE__)."/HEreceive" ?>
                                    </code>

                                </div>
                            <?php
} ?>

                                <a class="btn btn-warning" href="setting.php?section=genericRadio">Liste relais</a>
                                <div class="settings">
                                    <p>Pin Emetteur Radio</p>
                                    <input type="text" class="input-large" id="emiterPin" name="emiterPin" value="<?php echo $conf->get('plugin_genericRadio_emitter_pin');?>" placeholder="0">

                                    <p>Pin Récepteur Radio (pour détecter les codes radios)</p>
                                    <input type="text" class="input-large" id="receiverPin" name="receiverPin" value="<?php echo $conf->get('plugin_genericRadio_receiver_pin');?>" placeholder="7">
                                    <br>
                                    <button onclick="plugin_genericradio_save_settings(this);" class="btn">Enregistrer</button>
                                </div>
             
                            </div>
                            <h1>GPIO WiringPi</h1>
                            <img src="plugins/genericRadio/img/gpio.png">
                            <h1>Branchement</h1>
                            <img src="plugins/genericRadio/img/branchement.jpg">


                           
                        </div>
                    </div>

                    <?php
        } else {
            ?>

                    <div id="main" class="wrapper clearfix">
                        <article>
                            <h3>Vous devez être connecté</h3>
                        </article>
                    </div>
                    <?php

        }
    }
}



        Plugin::addCss("/css/main.css");
        Plugin::addJs("/js/main.js", true);

//Lie genericRadio_plugin_preference_menu au menu de réglages
        Plugin::addHook("preference_menu", "genericRadio_plugin_preference_menu");
//Lie genericRadio_plugin_preference_page a la page  de réglages
        Plugin::addHook("preference_content", "genericRadio_plugin_preference_page");
//Lie genericRadio_plugin_setting_page a la zone réglages
        Plugin::addHook("setting_bloc", "genericRadio_plugin_setting_page");
//Lie genericRadio_plugin_setting_menu au menu de réglages
        Plugin::addHook("setting_menu", "genericRadio_plugin_setting_menu");
//Lie genericradio_plugin_action a la page d'action qui permet d'effecuer des actions ajax 
//ou ne demandant pas de retour visuels
        Plugin::addHook("action_post_case", "genericradio_plugin_action");
//Lie genericradio_plugin_vocal_command a la gestion de commandes vocales proposées par yana
        Plugin::addHook("vocal_command", "genericradio_plugin_vocal_command");
//Lie genericRadio_plugin_widget aux widgets de la dashboard
        Plugin::addHook("widgets", "genericRadio_plugin_widget");

        ?>
