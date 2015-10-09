<?php
/**
 * Widget
 *
 * @package     LeaflyReviews\Widget
 * @since       1.0.0
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


/**
 * Leafly Reviews Widget
 *
 * @since       1.0.0
 */
class leaflyreviews_widget extends WP_Widget {

    /**
     * Constructor
     *
     * @access      public
     * @since       1.0.0
     * @return      void
     */
    public function leaflyreviews_widget() {
        parent::WP_Widget(
            false,
            __( 'Leafly Reviews', 'leafly-reviews' ),
            array(
                'description'  => __( 'Display your recent dispensary reviews from leafly.', 'leafly-reviews' )
            )
        );
    }

    /**
     * Widget definition
     *
     * @access      public
     * @since       1.0.0
     * @see         WP_Widget::widget
     * @param       array $args Arguments to pass to the widget
     * @param       array $instance A given widget instance
     * @return      void
     */
    public function widget( $args, $instance ) {
        if( ! isset( $args['id'] ) ) {
            $args['id'] = 'leafly_reviews_widget';
        }

        $title = apply_filters( 'widget_title', $instance['title'], $instance, $args['id'] );

        echo $args['before_widget'];

        if( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        do_action( 'leafly_reviews_before_widget' );

        if( $instance['slug'] ) {
			$data = wp_remote_get( 'http://data.leafly.com/locations/'.$instance["slug"].'/reviews?skip=0&take='.$instance["limit"].'',
				array(
					'headers' => array(
						'app_id' => $instance["appid"],
						'app_key' => $instance["appkey"]
					)
				)
			);
			
            if( $data['response']['message'] == "Forbidden" ) {
                echo $data['body'];
            } else {
				$i = 1;
				$body = json_decode($data['body'],true);
				foreach( $body['reviews'] as $review) {
					echo "<div class='leafly-reviews-plugin-meta'>";
					
					// Display the username who left the review
					echo "<p><span class='leafly-reviews-plugin-meta-username'>";
						// Display avatar if selected in the widget
						if('on' == $instance['avatar'] ) {
							echo "<img src='". $review['avatar'] ."' alt='". $review['username'] ."' class='leafly-reviews-plugin-meta-avatar' />";
						}
					echo "<strong>". $review['username'] ." </strong>";
						// Display star rating for the review
						if('on' == $instance['stars'] ) {
							echo "<span class='leafly-reviews-plugin-meta-image'><img class='leafly-reviews-plugin-meta-rating' src='". $review['starImage'] ." alt='Dispensary Review' /></span>";
						}
					echo "</span></p>"; // end username display

					if('on' == $instance['comments'] ) {
						// Display reviewer comments
						echo "<p><span class='leafly-reviews-plugin-meta-item'><strong>Comments: </strong><br />". $review['comments'] ."</span></p>";
					}
					
					if('on' == $instance['ratings'] ) {
						echo "<p>";
						// Display MEDS rating
						echo "<span class='leafly-reviews-plugin-meta-item'><strong>Meds: </strong>" . ( empty( $review['meds'] ) ? "not yet rated<br />" : $review['meds'] . " out of 5 stars</span><br />" );
						// Display SERVICE rating
						echo "<span class='leafly-reviews-plugin-meta-item'><strong>Service: </strong>" . ( empty( $review['service'] ) ? "not yet rated<br />" : $review['service'] . " out of 5 stars</span><br />" );
						// Display ATMOSPHERE rating
						echo "<span class='leafly-reviews-plugin-meta-item'><strong>Atmosphere: </strong>" . ( empty( $review['atmosphere'] ) ? "not yet rated<br />" : $review['atmosphere'] . " out of 5 stars</span><br />" );
						echo "</p>";
					}
					
					if('on' == $instance['recommend'] ) {
						// Display user recommendation if they say YES
						if ( $review['wouldRecommend'] == true ) {
							echo "<span class='leafly-reviews-plugin-meta-item'><strong>Would recommend: </strong>Yes</span><br />";
						}
					}

					if('on' == $instance['shopagain'] ) {
						// Display if user would shop again if they say YES
						if ( $review['shopAgain'] == true ) {
							echo "<span class='leafly-reviews-plugin-meta-item'><strong>Would shop again: </strong>Yes</span><br />";
						}
					}
					
					echo "</div>";
					
					// Check review count
					if ($i++ == $instance['limit']) break;
				}
            }
        } else {
            _e( 'No location has been specified!', 'leafly-reviews' );
        }

        do_action( 'leafly_reviews_after_widget' );
        
        echo $args['after_widget'];
    }


    /**
     * Update widget options
     *
     * @access      public
     * @since       1.0.0
     * @see         WP_Widget::update
     * @param       array $new_instance The updated options
     * @param       array $old_instance The old options
     * @return      array $instance The updated instance options
     */
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        $instance['title']      	= strip_tags( $new_instance['title'] );
        $instance['slug']   		= strip_tags( $new_instance['slug'] );
        $instance['appid']   		= strip_tags( $new_instance['appid'] );
        $instance['appkey']   		= strip_tags( $new_instance['appkey'] );
        $instance['limit']   		= strip_tags( $new_instance['limit'] );
        $instance['avatar']			= $new_instance['avatar'];
        $instance['stars']			= $new_instance['stars'];
        $instance['ratings']		= $new_instance['ratings'];
        $instance['recommend']		= $new_instance['recommend'];
        $instance['shopagain']		= $new_instance['shopagain'];
        $instance['comments']		= $new_instance['comments'];

        return $instance;
    }


    /**
     * Display widget form on dashboard
     *
     * @access      public
     * @since       1.0.0
     * @see         WP_Widget::form
     * @param       array $instance A given widget instance
     * @return      void
     */
    public function form( $instance ) {
        $defaults = array(
            'title'     => 'Leafly Reviews',
            'slug'  	=> '',
            'appid'		=> '',
            'appkey'	=> '',
            'limit'  	=> '5',
            'avatar' 	=> '',
            'stars' 	=> '',
            'ratings' 	=> '',
            'recommend' => '',
            'shopagain' => '',
            'comments'  => ''
        );

        $instance = wp_parse_args( (array) $instance, $defaults );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'leafly-reviews' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo $instance['title']; ?>" />
        </p>
		
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'appid' ) ); ?>"><?php _e( 'APP ID:', 'leafly-reviews' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'appid' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'appid' ) ); ?>" type="text" value="<?php echo $instance['appid']; ?>" />
        </p>
		
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'appkey' ) ); ?>"><?php _e( 'APP Key:', 'leafly-reviews' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'appkey' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'appkey' ) ); ?>" type="text" value="<?php echo $instance['appkey']; ?>" />
        </p>
		
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'slug' ) ); ?>"><?php _e( 'Location slug (ex: denver-relief):', 'leafly-reviews' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'slug' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'slug' ) ); ?>" type="text" value="<?php echo $instance['slug']; ?>" />
        </p>
		
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"><?php _e( 'Amount of reviews to show:', 'leafly-reviews' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>" type="number" name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>" min="1" max="999" value="<?php echo $instance['limit']; ?>" />
        </p>
		
	    <p>
			<input class="checkbox" type="checkbox" <?php checked($instance['avatar'], 'on'); ?> id="<?php echo $this->get_field_id('avatar'); ?>" name="<?php echo $this->get_field_name('avatar'); ?>" /> 
			<label for="<?php echo esc_attr( $this->get_field_id( 'avatar' ) ); ?>"><?php _e( 'Display user avatar?', 'leafly-reviews' ); ?></label>
        </p>

	    <p>
			<input class="checkbox" type="checkbox" <?php checked($instance['stars'], 'on'); ?> id="<?php echo $this->get_field_id('stars'); ?>" name="<?php echo $this->get_field_name('stars'); ?>" /> 
			<label for="<?php echo esc_attr( $this->get_field_id( 'stars' ) ); ?>"><?php _e( 'Display star rating image?', 'leafly-reviews' ); ?></label>
        </p>

	    <p>
			<input class="checkbox" type="checkbox" <?php checked($instance['ratings'], 'on'); ?> id="<?php echo $this->get_field_id('ratings'); ?>" name="<?php echo $this->get_field_name('ratings'); ?>" /> 
			<label for="<?php echo esc_attr( $this->get_field_id( 'ratings' ) ); ?>"><?php _e( 'Display detailed ratings?', 'leafly-reviews' ); ?></label>
        </p>

	    <p>
			<input class="checkbox" type="checkbox" <?php checked($instance['recommend'], 'on'); ?> id="<?php echo $this->get_field_id('recommend'); ?>" name="<?php echo $this->get_field_name('recommend'); ?>" /> 
			<label for="<?php echo esc_attr( $this->get_field_id( 'recommend' ) ); ?>"><?php _e( 'Display if reviewer recommends you?', 'leafly-reviews' ); ?></label>
        </p>

	    <p>
			<input class="checkbox" type="checkbox" <?php checked($instance['shopagain'], 'on'); ?> id="<?php echo $this->get_field_id('shopagain'); ?>" name="<?php echo $this->get_field_name('shopagain'); ?>" /> 
			<label for="<?php echo esc_attr( $this->get_field_id( 'shopagain' ) ); ?>"><?php _e( 'Display if reviewer would shop again?', 'leafly-reviews' ); ?></label>
        </p>

	    <p>
			<input class="checkbox" type="checkbox" <?php checked($instance['comments'], 'on'); ?> id="<?php echo $this->get_field_id('comments'); ?>" name="<?php echo $this->get_field_name('comments'); ?>" /> 
			<label for="<?php echo esc_attr( $this->get_field_id( 'comments' ) ); ?>"><?php _e( 'Display reviewer comments?', 'leafly-reviews' ); ?></label>
        </p>

		<?php
    }
}


/**
 * Register the new widget
 *
 * @since       1.0.0
 * @return      void
 */
function leaflyreviews_register_widget() {
    register_widget( 'leaflyreviews_widget' );
}
add_action( 'widgets_init', 'leaflyreviews_register_widget' );
