<?php
class fpinstagram {
   
    /* Aarons FPInstagram v0.8
     * For displaying Instagram on PHP websites
     * Simply include this file in /inc/ then include it!
     * Make sure you call it and all that stuff!
     */

    private $access_token;
    private $photo_count;
    private $json_link = 'https://api.instagram.com/v1/users/self/media/recent/?';
    private $json_data;
    private $admin_email = '';
    private $domain_email;
    private $holder_class = '';
    private $displayType = 'list'; // options are owl and list


    function __construct($access_token, $photo_count = 15) {
        $this->access_token = $access_token;
        $this->photo_count = $photo_count;
        echo $this->domain_email;
        if (strlen($access_token) < 1) {
            echo 'Access token is needed. Please visit http://instagram.pixelunion.net/';
        }
    }


    private function file_get_contents_curl($url) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }
    
    function setAccessToken($token) {
        $this->access_token = $token;
    }
    function getAccessToken() {
        return $this->access_token;
    }
    function setAdminEmail($email) {
        $this->admin_email = $email;
    }
    function setDomainEmail($email) {
        $this->domain_email = $email;
    }

    function setHolderClass($class) {
        $this->holder_class = $class;
    }

    function getError() {
        return $this->error;
    }
    function connect() {
        $json_link=$this->json_link;
        $json_link.="access_token={$this->access_token}&count={$this->photo_count}";
        $json = $this->file_get_contents_curl($json_link);
        $this->json_data = json_decode($json, true, 512, JSON_BIGINT_AS_STRING);
        if ($this->json_data['meta']['code'] == '200') {
            $this->error = false;
            unlink('./instagram_message_sent.txt');
            return true;
        } else {
            $this->error = $this->json_data['meta']['error_message'];
            $this->notify_admin();
            return false;
        }

    }

    function notify_admin() {
        // Method to notify the administrative e-mail on file that the Instagram is not working. 
        // This will only be triggered when a visitor visits the page the first time the connection does not work.
        // One single e-mail will be dispatched to the admin e-mail. A file will be created on the server
        // so that subsequent e-mails are not sent. Once the authentication token has been updated
        // the script will automatically remove the file.

        $message = 'This is a notice that the Instagram feed on [' . "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" . '] does not appear to be working. The Instagram feed has returned the message: [' . $this->error . '].';
        $headers = 'From: ' . $this->domain_email . "\r\n" .
        'X-Mailer: PHP/' . phpversion(); 
        if (!file_exists('./instagram_message_sent.txt')) {
            if (mail($this->admin_email,'Instagram Error on ' . $_SERVER['HTTP_HOST'],$message,$headers)) {
                echo '<!-- Hosting company has been notified of Authorization issue -->';

                $trackingFile = fopen("instagram_message_sent.txt", "w");
                $content = 'Instagram is not working for token: ' . $this->access_token;
                fwrite($trackingFile,$content);
                fclose($trackingFile);

            } else {
                echo '<!-- Cant send mail from Instagram! -->';
                error_log('Cant send mail from Instagram! Check the FPInstagram script');
            }
        } else {
            echo '<!-- Hosting company has already been notified of Authorization issue -->';

        }
       
    }

    function footerScripts() {
        echo '<script>jQuery(document).ready(function ($) {
             $(".insta-feed li").click(function () {
            if ($(this).children("video").length > 0)  {
                if ($(this).find("i").hasClass("play")) {
                    $(this).children("video")[0].pause();
                    $(this).find("i").removeClass("play");
                } else {
                    $(this).children("video")[0].play();
                    $(this).find("i").addClass("play");
                }
            }
            });
        });</script>';
        
        if ($this->displayType == 'owl') {
            echo '<style>
        
            .howl-carousel {
                position: relative;
            }
            .howl-carousel .owl-prev, .howl-carousel .owl-next {
                position: absolute;
                font-size: 2em;
                margin: 0 5px!important;
                width: 40px;
                background-color: rgba(255,255,255,0.5);
                text-align: center!important;
                top: 123px;
                transition: all 250ms ease;
            }
            .howl-carousel .owl-prev:hover, .howl-carousel .owl-next:hover {
                background-color: rgba(255,255,255,1);
            }
            .howl-carousel .owl-prev {
                left: 0px;
                
            }
            .howl-carousel .owl-next {
                right: 0px;
                
            }
            .howl-carousel .owl-prev:before {
                content: "\f3d2";
                font-family: "Ionicons";
            }
            .howl-carousel .owl-next:after {
                content: "\f3d3";
                font-family: "Ionicons";
            }
                </style>';
        } elseif ($this->displayType == 'list') {
            echo '<style></style>';
        }
    }
    function display() {
            $k ='';
        
            $k .= '<ul class="insta-feed ' . $this->holder_class . '">';
            $obj = $this->json_data;
            foreach ($obj['data'] as $post) {

                $pic_text=$post['caption']['text'];
                $pic_link=$post['link'];
                $pic_like_count=$post['likes']['count'];
                $pic_comment_count=$post['comments']['count'];
                $pic_src=str_replace("http://", "https://", $post['images']['standard_resolution']['url']);
                $pic_created_time=date("F j, Y", $post['caption']['created_time']);
                $pic_created_time=date("F j, Y", strtotime($pic_created_time . " +1 days"));
                //print_r($post);
                if (in_array('beforeandafter',$post['tags'])  || 1==1) {

                    $k .= "<li>";

                    if ($post['videos']['low_resolution']) {
                                    $k .= '<i class="icon-play"></i>';
                        $k .= '<video class="video" width="100%" height="100%" loop>';
                        $k .= '<source src="' . $post['videos']['low_resolution']['url'] . '" type="video/mp4"/>';
                        $k .= '</video>';
                        $k .= '<div class="controls"><a href="#" class="play_btn"><i class="ion-play"></i></a></div>';

                    } else {
                        $k .= "<a href='{$pic_link}' target='_blank' style='background-image: url({$pic_src}); background-size: cover; background-position: center center'>";
                        $k .= "<img class='img-responsive photo-thumb' src='{$pic_src}' alt='{$pic_text}' style='display: none;'>";
                        $k .= "</a>";
                        $k .= "<div class='insta-desc'>";
                        //echo "<a href='{$pic_link}' target='_blank'>{$pic_created_time}</a>";
                        $k .= "<a href='{$pic_link}' target='_blank'>{$pic_text}</a>";

                        //echo "<p>{$pic_text}</p>";
                        $k .= "</div>";

                    }


                    $k .= "</li>";
                }


            }
            $k .= '</ul>';
        
        return $k;

    }
}


?>