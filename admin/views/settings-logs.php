<?php
/**
 * 
 */

$args = array(
    'paged'     => 1,
    'per_page'  => 50
);

if( isset( $_GET['orderby'] ) && !empty( $_GET['orderby'] ) ) {
    $args['orderby'] = $_GET['orderby'];
}

if( isset( $_GET['order'] ) && !empty( $_GET['order'] ) ) {
    $args['order'] = $_GET['order'];
}

if( isset( $_GET['paged'] ) && !empty( $_GET['paged'] ) ) {
    $args['paged'] = $_GET['paged'];
}
?>
<div class="voy-row">
    <div class="voy-col-6">
        <h3><?php printf( __('%d Résultats', 'notifications-center'), VOYNOTIF_logs::get_logs_count($args) ); ?></h3>
    </div>
    <div class="voy-col-6 voy-text-right">
        <form action="<?php echo remove_query_arg('s'); ?>" method="get">
            <input type="search" id="post-search-input" name="s" value="">
            <input type="submit" id="search-submit" class="button" value="Search Posts">             
        </form>       
    </div>
</div>

<table class="voynotif_list_logs">
    <thead>
        <tr>
            <th>ID</th>            
            <th>            
                <a href="<?php echo VOYNOTIF_logs::get_order_url('date'); ?>"><?php _e('Date', 'notifications-center'); ?></a>
            </th>
            <th><?php _e('Notification', 'notifications-center'); ?></th>
            <th>
                <a href="<?php echo VOYNOTIF_logs::get_order_url('type'); ?>"><?php _e('Type', 'notifications-center'); ?></a>
            </th>
            <th>
                <a href="<?php echo VOYNOTIF_logs::get_order_url('recipient'); ?>"><?php _e('Recipient', 'notifications-center'); ?></a>
            </th>
            <th>
                <a href="<?php echo VOYNOTIF_logs::get_order_url('subject'); ?>"><?php _e('Subject', 'notifications-center'); ?></a>
            </th>
            <th>
                <?php _e('Context', 'notifications-center'); ?>
            </th>
            <th>
                <a href="<?php echo VOYNOTIF_logs::get_order_url('status'); ?>"><?php _e('Status', 'notifications-center'); ?></a>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php if (VOYNOTIF_logs::get_logs()) { ?>
            <?php foreach (VOYNOTIF_logs::get_logs( $args ) as $log) { ?>
                <?php
                $log_date = new DateTime($log->date);
                $log_context = unserialize($log->context);
                ?>
                <tr>
                    <td><?php echo $log->id; ?></td>
                    <td><?php echo $log_date->format('d/m/Y à G:i'); ?></td>
                    <td>
                        <?php if( get_post_type( $log->notification_id ) == 'voy_notification' ) { ?> 
                        <a href="<?php echo get_edit_post_link( $log->notification_id ); ?>"><?php echo get_the_title($log->notification_id); ?></a>
                        <?php } ?>                       
                    </td>
                    <td><?php echo voynotif_get_notification_type_title($log->type); ?></td>
                    <td><?php echo $log->recipient; ?></td>
                    <td><?php echo $log->subject; ?></td>
                    <td>
                        <?php echo VOYNOTIF_logs::get_context_html($log_context, $log->type); ?>
                    </td>
                    <td>
                        <?php echo VOYNOTIF_logs::get_status_title( $log->status ); ?>
                    </td>
                </tr>
            <?php } ?>  
        <?php } ?>
    </tbody>
</table>
<?php 
$total_pages = ceil(VOYNOTIF_logs::get_logs_count($args) / $args['per_page']);
echo paginate_links( array(
	'base'               => '%_%',
	'format'             => '?paged=%#%',
	'total'              => $total_pages,
	'current'            => $args['paged'],
	'end_size'           => 3,
	'mid_size'           => 3,
	'prev_next'          => true,
) );
?>