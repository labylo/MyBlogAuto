<?php if ($signature_text) : ?>
Bien cordialement,
<?php echo SITE_NAME ?>

<?php echo SITE_URL ?>
<?php endif; ?>
			
<?php if ($noreply_text) : ?>
--
Cet email vous a été envoyé de façon automatique, merci de ne pas y répondre.
<?php endif; ?>		
<?php if ($desabo_text) : ?>
--
Pour ne plus recevoir ce type de message de la part <?php echo SITE_NAME ?>,
rendez-vous sur votre espace membre, puis cliquez sur "Mes alertes mail".
Choisissez alors de désactiver les alertes par mail que vous ne souhaitez plus recevoir.
<?php endif; ?>