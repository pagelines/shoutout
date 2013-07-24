<?php
/*
	Section: Shout Out
	Author: etc.io
	Author URI: http://shoutout.etc.io
	Version: 1.0
	Description: An easy way to announce or make mention of a list of companies with logos!
	Class Name: etcShoutOut
	Workswith: templates, main, header, footer, morefoot
	Filter: component
	Cloning: true
	V3: true
*/


class etcShoutOut extends PageLinesSection {

	var $default_limit = 3;

	function section_opts(){
	
		$soclass = sprintf('shoutout%sclass',$this->get_the_id());
		
		$instructions_template = <<<EOT
		<div>
			<p>Welcome to Shout Out.</p>
			<p>Whether they are partners, affiliates or press references, add their logos to your site easily. With a couple of clicks, set the opacity or turn on desaturation mode and the logos instantly are black and white. When you hover over them, it'll color up...magically.</p> 
			<p style="text-transform: uppercase;"><strong>Pro Tip</strong></p>
			<p><u>Full-Width</u> : You can color your Shout Out bar full width by placing it within a full-width section, and adding the following custom class to it.</em></p>
			<p style="text-align: center"><strong>$soclass</strong</p>
		</div>
EOT;
		
		$options = array();
		
		$options[] = array(
			'key'      => 'instructions',
			'type'     => 'template',
			'title'    => 'Instructions',
			'template' => $instructions_template
		);

		$options[] = array(

			'title' => __( 'Shout Out Configuration', 'shoutout' ),
			'type'	=> 'multi',
			'opts'	=> array(
				array( 
					'key'			=> 'shoutout_count',
					'type' 			=> 'count_select',
					'count_start'	=> 1,
					'count_number'	=> 12,
					'default'		=> 3,
					'label' 		=> __( 'Number of Shouts to Configure', 'shoutout' ),
				),
				array(
					'key'			=> 'shoutout_height',
					'type' 			=> 'text',
					'default'		=> 50,
					'label' 		=> 'Logo height (in px)',
				),
				array(
					'key'			=> 'shoutout_margin',
					'type' 			=> 'text',
					'default'		=> 15,
					'label' 		=> 'Padding',
				),
				array(
					'type'    => 'check',
					'default' => '0',
					'key'     => 'shoutout_bw',
					'label'   => 'Desaturation Effect'
				),
				array(
					'type'    => 'check',
					'default' => '0',
					'key'     => 'shoutout_window',
					'label'   => 'Open links in new window'
				),
				array(
					'type'    => 'select',
					'key'     => 'shoutout_opacity',
					'label'   => 'Opacity Effect',
					'default' => '1.0',
					'opts'    => array(
						'0.1'		=> array('name' => '10%'),
						'0.2'		=> array('name' => '20%'),
						'0.3'		=> array('name' => '30%'),
						'0.4'		=> array('name' => '40%'),
						'0.5'		=> array('name' => '50%'),
						'0.6'		=> array('name' => '60%'),
						'0.7'		=> array('name' => '70%'),
						'0.8'		=> array('name' => '80%'),
						'0.9'		=> array('name' => '90%'),						
						'1.0'		=> array('name' => '100%'),
					)
				),
				array(
					'type'    => 'check',
					'default' => '0',
					'key'     => 'shoutout_bg',
					'label'   => 'Set BG color'
				),
				array(
					'key'     		=> 'shoutout_color',
					'label'   		=> 'BG Color',
					'type'    		=> 'color',
					'default' 		=> 'FFFFFF'
				),
			)

		);

		$logos = ($this->opt('shoutout_count')) ? $this->opt('shoutout_count') : $this->default_limit;

		for($i = 1; $i <= $logos; $i++){

			$opts = array(

				array(
					'key'		=> 'shoutout_image_'.$i,
					'label'		=> __( 'Image', 'shoutout' ),
					'type'		=> 'image_upload',
				),
				array(
					'key'		=> 'shoutout_link_'.$i,
					'label'		=> __( 'Shout Link (Optional)', 'shoutout' ),
					'type'		=> 'text'
				),
				array(
					'key'		=> 'shoutout_alt_'.$i,
					'label'		=> __( 'Alt Text', 'shoutout' ),
					'type'		=> 'text'
				),
			);

			$options[] = array(
				'title' 	=> __( 'Shout ', 'shoutout' ) . $i,
				'type' 		=> 'multi',
				'opts' 		=> $opts,

			);

		}

		return $options;
	}

	function section_head() {
		$plid = sprintf('shoutout%s',$this->get_the_id());
		$width = ($this->opt('shoutout_count')) ? 'width: ' . 100/$this->opt('shoutout_count') . '%;' : 'width: ' . 100/$this->default_limit . '%;';
		$height = ($this->opt('shoutout_height')) ? sprintf('max-height: %dpx;',$this->opt('shoutout_height')) : 'max-height: 50px;';
		$lineheight = ($this->opt('shoutout_height')) ? sprintf('line-height: %dpx;',$this->opt('shoutout_height')) : 'line-height: 50px;';
		
		if($this->opt('shoutout_bg')){
			$color = ($this->opt('shoutout_color')) ? sprintf('background-color:#%s;',$this->opt('shoutout_color')) : 'background-color:#FFFFFF;';	
		} else {
			$color = '';
		}
		
		$opacity = ($this->opt('shoutout_opacity')) ? sprintf('opacity:%s;',$this->opt('shoutout_opacity')) : 'opacity:1.0;';
		$reset = '';

		if($opacity != 'opacity:1.0;' || $this->opt('shoutout_bw')) {
			$reset = <<<EOT
				#$plid .shout .shout-media img {
				  -webkit-transition: all .2s ease-in-out;
				  -moz-transition: all .2s ease-in-out;
				  -o-transition: all .2s ease-in-out;
				  transition: all .2s ease-in-out;
				}
				
				#$plid .shout .shout-media:hover img {
					opacity: 1.0;
					filter: none;
				    -webkit-filter: grayscale(0);
				}
EOT;
		}
		
		$margin = ($this->opt('shoutout_margin')) ? sprintf('padding: 0 %spx;',$this->opt('shoutout_margin')) : 'padding: 0 15px;';
		$bw = ($this->opt('shoutout_bw')) ? sprintf('filter: url(%s/filters.svg#grayscale);filter: gray;-webkit-filter: grayscale(1);',		$this->base_url) : '';
		printf('<style>#%s .shout .shout-media img{%s %s %s}#%s,.%sclass{%s}#%s .shout{%s %s %s}%s</style>',$plid,$height,$opacity,$bw,$plid,$plid,$color,$plid,$margin,$width,$lineheight,$reset);
	}

   function section_template( ) {

		$shouts = ($this->opt('shoutout_count')) ? $this->opt('shoutout_count') : $this->default_limit;
		$window = ($this->opt('shoutout_window')) ? ' target=:"_blank"' : '';
		$sectionurl = $this->base_url;

		$output = '';
		
		if($this->opt('shoutout_link_1')){
			for($i = 1; $i <= $shouts; $i++):
				$link = $this->opt('shoutout_link_'.$i);
				$media_link = ($link) ? sprintf('href="%s"', $link) : '';
				$media = ($this->opt('shoutout_image_'.$i)) ? $this->opt('shoutout_image_'.$i) : false;
				$alt = ($this->opt('shoutout_alt_'.$i)) ? sprintf(' alt="%s"', $this->opt('shoutout_alt_'.$i)) : ' alt="Image"';
	
				$output .= sprintf(
					'<div class="shout">
						<div class="shout-media">
							<a %s%s>
								<span class="pl-animation pl-appear">
									<img src="%s"%s/>
								</span>
							</a>
						</div>
					</div>',
					$media_link,
					$window,
					$media,
					$alt
				);
			endfor;
		} else {
			$output .= <<<EOT
			<div class="shoutout-wrapper pl-animation-group fix"><div class="shoutout-floater">
				<div class="shout">
					<div class="shout-media">
						<a href="http://www.etc.io" target=:"_blank">
							<span class="pl-animation pl-appear">
								<img src="$sectionurl/img/etc.png" alt="etc"/>
							</span>
						</a>
					</div>
				</div>
				<div class="shout">
					<div class="shout-media">
						<a href="http://www.pagelines.com" target=:"_blank">
							<span class="pl-animation pl-appear">
								<img src="$sectionurl/img/pagelines.png" alt="Pagelines"/>
							</span>
						</a>
					</div>
				</div>
				<div class="shout">
					<div class="shout-media">
						<a href="http://www.wordpress.org" target=:"_blank">
							<span class="pl-animation pl-appear">
								<img src="$sectionurl/img/wp.png" alt="Wordpress"/>
							</span>
						</a>
					</div>
				</div>
			</div>
EOT;
		}

		printf('<div class="shoutout-wrapper pl-animation-group fix"><div class="shoutout-floater">%s</div></div>', $output);

	}

}