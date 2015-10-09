<?php
/**
 * Shortcodes
 *
 * @package     LeaflyReviews\Shortcodes
 * @since       1.0.0
 */
// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;
/**
 * Tally Shortcode
 *
 * @since       1.0.0
 * @param       array $atts Shortcode attributes
 * @param       string $content
 * @return      string $return The LeaflyReviews
 */
 
function leafly_reviews_shortcode($atts){
	
	extract(shortcode_atts(array(
		'slug' => '',
		'appid' => '',
		'appkey' => '',
		'limit' => '5',
		'avatar' => 'yes',
		'stars' => 'yes',
		'ratings' => 'yes',
		'recommend' => 'yes',
		'shopagain' => 'yes',
		'comments' => 'yes',
	), $atts));
	
	ob_start();

        if( $slug !== '' ) {
			$data = wp_remote_get( 'http://data.leafly.com/locations/'.$slug.'/reviews?skip=0&take='.$limit.'',
				array(
					'headers' => array(
						'app_id' => $appid,
						'app_key' => $appkey
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
						if('yes' == $avatar ) {
							echo "<img src='". $review['avatar'] ."' alt='". $review['username'] ."' class='leafly-reviews-plugin-meta-avatar' />";
						}
					echo "<strong>". $review['username'] ." </strong>";
						// Display star rating for the review
						if('yes' == $stars ) {
							echo "<span class='leafly-reviews-plugin-meta-image'><img class='leafly-reviews-plugin-meta-rating' src='". $review['starImage'] ." alt='Dispensary Review' /></span>";
						}
					echo "</span></p>"; // end username display

					if('yes' == $comments ) {
						// Display reviewer comments
						echo "<p><span class='leafly-reviews-plugin-meta-item'><strong>Comments: </strong><br />". $review['comments'] ."</span></p>";
					}
					
					if('yes' == $ratings ) {
						echo "<p>";
						// Display MEDS rating
						echo "<span class='leafly-reviews-plugin-meta-item'><strong>Meds: </strong>" . ( empty( $review['meds'] ) ? "not yet rated<br />" : $review['meds'] . " out of 5 stars</span><br />" );
						// Display SERVICE rating
						echo "<span class='leafly-reviews-plugin-meta-item'><strong>Service: </strong>" . ( empty( $review['service'] ) ? "not yet rated<br />" : $review['service'] . " out of 5 stars</span><br />" );
						// Display ATMOSPHERE rating
						echo "<span class='leafly-reviews-plugin-meta-item'><strong>Atmosphere: </strong>" . ( empty( $review['atmosphere'] ) ? "not yet rated<br />" : $review['atmosphere'] . " out of 5 stars</span><br />" );
						echo "</p>";
					}
					
					if('yes' == $recommend ) {
						// Display user recommendation if they say YES
						if ( $review['wouldRecommend'] == true ) {
							echo "<span class='leafly-reviews-plugin-meta-item'><strong>Would recommend: </strong>Yes</span><br />";
						}
					}

					if('yes' == $shopagain ) {
						// Display if user would shop again if they say YES
						if ( $review['shopAgain'] == true ) {
							echo "<span class='leafly-reviews-plugin-meta-item'><strong>Would shop again: </strong>Yes</span><br />";
						}
					}
					
					echo "</div>";
					
					// Check review count
					if ($i++ == $limit) break;
				}
            }
        } else {
            _e( 'No location has been specified!', 'leafly-reviews' );
        }
		
		$output_string=ob_get_contents();
		ob_end_clean();

		return $output_string;

}

add_shortcode('leaflyreviews', 'leafly_reviews_shortcode');
 
?>