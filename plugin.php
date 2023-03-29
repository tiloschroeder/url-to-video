<?php

class urlToVideo extends Plugin {

    public function init()
    {
        $this->dbFields = array(
                'css-head' => 'no'
        );

    }

    public function form()
    {
        global $L;

        $html  = '<div class="alert alert-info" role="alert">';
        $html .= $this->description();
        $html .= '</div>';

        $html .= '<!-- TABS -->';
        $html .= '<nav class="mb-3">';
        $html .= '<div class="nav nav-tabs" id="nav-tab" role="tablist">';
        $html .= '<a class="nav-item nav-link active" id="nav-settings-tab" data-toggle="tab" href="#settings" role="tab" aria-controls="panel-settings" aria-selected="true">' . $L->get("first-tab") . '</a>';
        $html .= '<a class="nav-item nav-link" id="nav-usage-tab" data-toggle="tab" href="#usage" role="tab" aria-controls="panel-usage" aria-selected="false">' . $L->get("second-tab") . '</a>';
        $html .= '</div>';
        $html .= '</nav>';

        $html .= '<div class="tab-content">';
        $html .= '<!-- Tab settings -->';
        $html .= '<div class="tab-pane fade show active" id="settings" role="tabpanel" aria-labelledby="nav-settings-tab">';
        $html .= '<h6 class="mt-4 mb-2 pb-2 border-bottom text-uppercase">' . $L->get("bootstrap-theme-support") . '</h6>';
        $html .= '<p>' . $L->get('content-bootstrap-support') . '</p>';
        $html .= '<h6 class="mt-4 mb-2 pb-2 border-bottom text-uppercase">' . $L->get("no-bootstrap-theme-support") . '</h6>';
        $html .= '<p>' . $L->get('content-no-bootstrap-support') . '</p>';
        $html .= '<input type="hidden" name="css-head" value="no" />';
        $html .= '<label class="alert alert-secondary ml-4 mr-4"><input type="checkbox" name="css-head" value="yes" ' . ($this->getValue('css-head') === "yes" ? 'checked' : '') . '/> ' . $L->get('include-css-yes') . '</label>';
        $html .= '</div>';
        $html .= '<!-- Tab usage -->';
        $html .= '<div class="tab-pane fade" id="usage" role="tabpanel" aria-labelledby="nav-usage-tab">';
        $html .= '<p class="mt-4">' . $L->get('usage-example-text') . '</p>';
        $html .= '<div class="alert alert-light border ml-4 mr-4"><span style="background-color:var(--gray)">&nbsp;</span><pre>&lt;https://youtu.be/hüTZeLkRütz3l&gt;</pre><span style="background-color:var(--gray)">&nbsp;</span></div>';
        $html .= '<p class="ml-4 mr-4 alert alert-info">' . $L->get('usage-notice-text') . '</p>';
        $html .= '</div>'>
        $html .= '</div>';

        return $html;

    }

    public function siteHead()
    {
        $outputCSS = $this->getValue('css-head');

        if ( $outputCSS == 'yes' ) {
            $html = $this->includeCSS('url-to-video.css');
            return $html;

        }

    }

    public function pageBegin()
    {
        ob_start();

    }

    public function pageEnd()
    {
        $content = ob_get_clean();

        $vWidth = 480;
        $vHeight = 270;
        $wrapperClass = "embed-responsive embed-responsive-16by9 ratio ratio-16x9";
        $iframeClass = "embed-responsive-item";

        $pattern = array(
                "vimeo" => '/<p><a [^>]*>https:\/\/vimeo\.com\/(.*?)<\/a\><\/p\>/',
                "youtu.be" => '/<p><a [^>]*>https:\/\/youtu\.be\/(.*?)<\/a\><\/p\>/'
            );

        $replacement = array(
                "vimeo" => '<div class="' . $wrapperClass . '"><iframe src="https://player.vimeo.com/video/$1" class="' . $iframeClass . '" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen title="Vimeo video" width="' . $vWidth . '" height="' . $vHeight . '" loading="lazy"></iframe></div>',
                "youtu.be" => '<div class="' . $wrapperClass . '"><iframe src="https://www.youtube-nocookie.com/embed/$1" class="' . $iframeClass . '" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen title="YouTube video" width="' . $vWidth . '" height="' . $vHeight . '" loading="lazy"></iframe></div>'
            );

        $content = preg_replace($pattern, $replacement, $content);

        return $content;

    }

}
