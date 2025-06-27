<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 *
 * @package ID_Basica
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div class="container">
	<div class="content__header">
		<h1 class="title">
			<?php esc_html_e( 'Dashboard', ID_BASICA_DOMAIN ); ?>
		</h1>
	</div>

	<div class="content__main">
		<!-- Dashboard Summary Widgets -->
		<div class="widgets-grid">
			<!-- Quick Stats Widget -->
			<div class="widget">
				<div class="widget__header">
					<h3 class="widget__title"><i class="fas fa-chart-line"></i> <?php esc_html_e( 'Quick Stats', ID_BASICA_DOMAIN ); ?></h3>
				</div>
				<div class="widget__content">
					<div class="stats-grid">
						<div class="stat-item">
							<span class="stat-value">12</span>
							<span class="stat-label"><?php esc_html_e( 'New Messages', ID_BASICA_DOMAIN ); ?></span>
						</div>
						<div class="stat-item">
							<span class="stat-value">5</span>
							<span class="stat-label"><?php esc_html_e( 'Tasks', ID_BASICA_DOMAIN ); ?></span>
						</div>
						<div class="stat-item">
							<span class="stat-value">3</span>
							<span class="stat-label"><?php esc_html_e( 'Events Today', ID_BASICA_DOMAIN ); ?></span>
						</div>
						<div class="stat-item">
							<span class="stat-value">28</span>
							<span class="stat-label"><?php esc_html_e( 'New Documents', ID_BASICA_DOMAIN ); ?></span>
						</div>
					</div>
				</div>
			</div>

			<!-- Recent Activity Widget -->
			<div class="widget">
				<div class="widget__header">
					<h3><i class="fas fa-history"></i> <?php esc_html_e( 'Recent Activity', ID_BASICA_DOMAIN ); ?></h3>
				</div>
				<div class="widget__content">
					<ul class="activity-list">
						<li class="activity-item">
							<div class="activity-icon"><i class="fas fa-file-alt"></i></div>
							<div class="activity-content">
								<div class="activity-title"><?php esc_html_e( 'New Document Added', ID_BASICA_DOMAIN ); ?></div>
								<div class="activity-meta"><?php esc_html_e( '2 hours ago', ID_BASICA_DOMAIN ); ?></div>
							</div>
						</li>
						<li class="activity-item">
							<div class="activity-icon"><i class="fas fa-user"></i></div>
							<div class="activity-content">
								<div class="activity-title"><?php esc_html_e( 'Profile Updated', ID_BASICA_DOMAIN ); ?></div>
								<div class="activity-meta"><?php esc_html_e( 'Yesterday', ID_BASICA_DOMAIN ); ?></div>
							</div>
						</li>
						<li class="activity-item">
							<div class="activity-icon"><i class="fas fa-comment"></i></div>
							<div class="activity-content">
								<div class="activity-title"><?php esc_html_e( 'New Comment', ID_BASICA_DOMAIN ); ?></div>
								<div class="activity-meta"><?php esc_html_e( '3 days ago', ID_BASICA_DOMAIN ); ?></div>
							</div>
						</li>
					</ul>
				</div>
			</div>

			<!-- Calendar Widget -->
			<div class="widget">
				<div class="widget__header">
					<h3><i class="fas fa-calendar-alt"></i> <?php esc_html_e( 'Upcoming Events', ID_BASICA_DOMAIN ); ?></h3>
				</div>
				<div class="widget__content">
					<ul class="event-list">
						<li class="event-item">
							<div class="event-date">
								<span class="event-month"><?php esc_html_e( 'JUN', ID_BASICA_DOMAIN ); ?></span>
								<span class="event-day">5</span>
							</div>
							<div class="event-content">
								<div class="event-title"><?php esc_html_e( 'Team Meeting', ID_BASICA_DOMAIN ); ?></div>
								<div class="event-meta"><i class="fas fa-clock"></i> 10:00 AM</div>
							</div>
						</li>
						<li class="event-item">
							<div class="event-date">
								<span class="event-month"><?php esc_html_e( 'JUN', ID_BASICA_DOMAIN ); ?></span>
								<span class="event-day">10</span>
							</div>
							<div class="event-content">
								<div class="event-title"><?php esc_html_e( 'Project Deadline', ID_BASICA_DOMAIN ); ?></div>
								<div class="event-meta"><i class="fas fa-clock"></i> 5:00 PM</div>
							</div>
						</li>
						<li class="event-item">
							<div class="event-date">
								<span class="event-month"><?php esc_html_e( 'JUN', ID_BASICA_DOMAIN ); ?></span>
								<span class="event-day">15</span>
							</div>
							<div class="event-content">
								<div class="event-title"><?php esc_html_e( 'Client Presentation', ID_BASICA_DOMAIN ); ?></div>
								<div class="event-meta"><i class="fas fa-clock"></i> 2:30 PM</div>
							</div>
						</li>
					</ul>
				</div>
			</div>

			<!-- Quick Actions Widget -->
			<div class="widget">
				<div class="widget__header">
					<h3><i class="fas fa-bolt"></i> <?php esc_html_e( 'Quick Actions', ID_BASICA_DOMAIN ); ?></h3>
				</div>
				<div class="widget__content">
					<div class="quick-actions">
						<a href="#" class="quick-action-item">
							<i class="fas fa-file-alt"></i>
							<span><?php esc_html_e( 'New Document', ID_BASICA_DOMAIN ); ?></span>
						</a>
						<a href="#" class="quick-action-item">
							<i class="fas fa-calendar-plus"></i>
							<span><?php esc_html_e( 'Add Event', ID_BASICA_DOMAIN ); ?></span>
						</a>
						<a href="#" class="quick-action-item">
							<i class="fas fa-user-plus"></i>
							<span><?php esc_html_e( 'Add User', ID_BASICA_DOMAIN ); ?></span>
						</a>
						<a href="#" class="quick-action-item">
							<i class="fas fa-envelope"></i>
							<span><?php esc_html_e( 'Send Message', ID_BASICA_DOMAIN ); ?></span>
						</a>
					</div>
				</div>
			</div>
		</div>

		<!-- Recent Documents Card -->
		<?php
		ob_start();
		?>
		<div class="table-responsive">
			<table>
				<thead>
					<tr>
						<th><?php esc_html_e( 'Document', ID_BASICA_DOMAIN ); ?></th>
						<th><?php esc_html_e( 'Category', ID_BASICA_DOMAIN ); ?></th>
						<th><?php esc_html_e( 'Author', ID_BASICA_DOMAIN ); ?></th>
						<th><?php esc_html_e( 'Date', ID_BASICA_DOMAIN ); ?></th>
						<th><?php esc_html_e( 'Actions', ID_BASICA_DOMAIN ); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							<a href="#" class="document-link">
								<i class="fas fa-file-pdf"></i> <?php esc_html_e( 'Company Policy', ID_BASICA_DOMAIN ); ?>
							</a>
						</td>
						<td><?php esc_html_e( 'HR', ID_BASICA_DOMAIN ); ?></td>
						<td><?php esc_html_e( 'Admin', ID_BASICA_DOMAIN ); ?></td>
						<td><?php esc_html_e( 'Jun 1, 2025', ID_BASICA_DOMAIN ); ?></td>
						<td>
							<div class="table-actions">
								<a href="#" class="action-link" data-tooltip="<?php esc_attr_e( 'View', ID_BASICA_DOMAIN ); ?>">
									<i class="fas fa-eye"></i>
								</a>
								<a href="#" class="action-link" data-tooltip="<?php esc_attr_e( 'Download', ID_BASICA_DOMAIN ); ?>">
									<i class="fas fa-download"></i>
								</a>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<a href="#" class="document-link">
								<i class="fas fa-file-word"></i> <?php esc_html_e( 'Project Proposal', ID_BASICA_DOMAIN ); ?>
							</a>
						</td>
						<td><?php esc_html_e( 'Projects', ID_BASICA_DOMAIN ); ?></td>
						<td><?php esc_html_e( 'John Doe', ID_BASICA_DOMAIN ); ?></td>
						<td><?php esc_html_e( 'May 28, 2025', ID_BASICA_DOMAIN ); ?></td>
						<td>
							<div class="table-actions">
								<a href="#" class="action-link" data-tooltip="<?php esc_attr_e( 'View', ID_BASICA_DOMAIN ); ?>">
									<i class="fas fa-eye"></i>
								</a>
								<a href="#" class="action-link" data-tooltip="<?php esc_attr_e( 'Download', ID_BASICA_DOMAIN ); ?>">
									<i class="fas fa-download"></i>
								</a>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<a href="#" class="document-link">
								<i class="fas fa-file-excel"></i> <?php esc_html_e( 'Monthly Report', ID_BASICA_DOMAIN ); ?>
							</a>
						</td>
						<td><?php esc_html_e( 'Finance', ID_BASICA_DOMAIN ); ?></td>
						<td><?php esc_html_e( 'Jane Smith', ID_BASICA_DOMAIN ); ?></td>
						<td><?php esc_html_e( 'May 15, 2025', ID_BASICA_DOMAIN ); ?></td>
						<td>
							<div class="table-actions">
								<a href="#" class="action-link" data-tooltip="<?php esc_attr_e( 'View', ID_BASICA_DOMAIN ); ?>">
									<i class="fas fa-eye"></i>
								</a>
								<a href="#" class="action-link" data-tooltip="<?php esc_attr_e( 'Download', ID_BASICA_DOMAIN ); ?>">
									<i class="fas fa-download"></i>
								</a>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<?php
		$card_content = ob_get_clean();

		$card_footer = '<a href="#" class="btn btn--primary">' . esc_html__( 'View All Documents', ID_BASICA_DOMAIN ) . '</a>';

		id_basica_card(
			esc_html__( 'Recent Documents', ID_BASICA_DOMAIN ),
			$card_content,
			array(
				'footer' => $card_footer,
				'icon'   => 'fas fa-file-alt',
			)
		);
		?>

		<!-- Tasks Overview Card -->
		<?php
		ob_start();
		?>
		<div class="task-list">
			<div class="task-item">
				<div class="task-checkbox">
					<input type="checkbox" id="task1" name="task1">
					<label for="task1"></label>
				</div>
				<div class="task-content">
					<div class="task-title"><?php esc_html_e( 'Complete project proposal', ID_BASICA_DOMAIN ); ?></div>
					<div class="task-meta">
						<span class="task-due"><i class="fas fa-calendar"></i> <?php esc_html_e( 'Due: Jun 5, 2025', ID_BASICA_DOMAIN ); ?></span>
						<span class="task-priority task-priority--high"><?php esc_html_e( 'High', ID_BASICA_DOMAIN ); ?></span>
					</div>
				</div>
				<div class="task-actions">
					<a href="#" class="action-link" data-tooltip="<?php esc_attr_e( 'Edit', ID_BASICA_DOMAIN ); ?>">
						<i class="fas fa-edit"></i>
					</a>
					<a href="#" class="action-link" data-tooltip="<?php esc_attr_e( 'Delete', ID_BASICA_DOMAIN ); ?>">
						<i class="fas fa-trash"></i>
					</a>
				</div>
			</div>
			<div class="task-item">
				<div class="task-checkbox">
					<input type="checkbox" id="task2" name="task2">
					<label for="task2"></label>
				</div>
				<div class="task-content">
					<div class="task-title"><?php esc_html_e( 'Schedule team meeting', ID_BASICA_DOMAIN ); ?></div>
					<div class="task-meta">
						<span class="task-due"><i class="fas fa-calendar"></i> <?php esc_html_e( 'Due: Jun 7, 2025', ID_BASICA_DOMAIN ); ?></span>
						<span class="task-priority task-priority--medium"><?php esc_html_e( 'Medium', ID_BASICA_DOMAIN ); ?></span>
					</div>
				</div>
				<div class="task-actions">
					<a href="#" class="action-link" data-tooltip="<?php esc_attr_e( 'Edit', ID_BASICA_DOMAIN ); ?>">
						<i class="fas fa-edit"></i>
					</a>
					<a href="#" class="action-link" data-tooltip="<?php esc_attr_e( 'Delete', ID_BASICA_DOMAIN ); ?>">
						<i class="fas fa-trash"></i>
					</a>
				</div>
			</div>
			<div class="task-item">
				<div class="task-checkbox">
					<input type="checkbox" id="task3" name="task3">
					<label for="task3"></label>
				</div>
				<div class="task-content">
					<div class="task-title"><?php esc_html_e( 'Review monthly reports', ID_BASICA_DOMAIN ); ?></div>
					<div class="task-meta">
						<span class="task-due"><i class="fas fa-calendar"></i> <?php esc_html_e( 'Due: Jun 12, 2025', ID_BASICA_DOMAIN ); ?></span>
						<span class="task-priority task-priority--low"><?php esc_html_e( 'Low', ID_BASICA_DOMAIN ); ?></span>
					</div>
				</div>
				<div class="task-actions">
					<a href="#" class="action-link" data-tooltip="<?php esc_attr_e( 'Edit', ID_BASICA_DOMAIN ); ?>">
						<i class="fas fa-edit"></i>
					</a>
					<a href="#" class="action-link" data-tooltip="<?php esc_attr_e( 'Delete', ID_BASICA_DOMAIN ); ?>">
						<i class="fas fa-trash"></i>
					</a>
				</div>
			</div>
		</div>
		<?php
		$card_content = ob_get_clean();

		$card_footer = '<a href="#" class="btn btn--primary">' . esc_html__( 'View All Tasks', ID_BASICA_DOMAIN ) . '</a>';

		id_basica_card(
			esc_html__( 'Tasks Overview', ID_BASICA_DOMAIN ),
			$card_content,
			array(
				'footer' => $card_footer,
				'icon'   => 'fas fa-tasks',
			)
		);
		?>
	</div>
</div>

<?php
get_footer();
