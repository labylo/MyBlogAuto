<?php if ($signature_text) : ?>
<br/><br/>
<p style="text-align:left">
Bien cordialement,<br/>
<em><a href="<?php echo SITE_URL ?>" target="_blank"><?php echo SITE_DOMAINE ?></a></em><br/>
</p>
<?php endif; ?>

<?php if ($noreply_text) : ?>
<hr style="border:0;border-bottom:1px solid #cccccc;height:1px;" />
<p style="text-align:center;">
	Ce message vous a été envoyé automatiquement, merci de ne pas y répondre.
</p>
<br/>
<?php endif; ?>

<!-- fin contenu -->

								</td>
							</tr>
						</table>
					</td>
				</tr>
				<!--  FOOTER / -->
				
				
				<tr>
					<td valign="top" align="center">
						<p style="color:#999;padding:0 20px;line-height:18px;">
						<!-- désabonnement -->
						<?php if ($desabo_text) : ?>
							TODO texte pour se désabonner
						<?php endif ?>
						
						<?php if ($desabo_link) : ?>
						<?php 
						if ( ! PRODUCTION ) {
							echo "<br>--------DEV : Ici prochainement un lien de désinscription------";
						}else{
							//Je met en commentaire car ps encore fait !
							/*
							Cliquez <a href="<?php echo $site_desabo_link ?>" target="_blank" title="Se désabonner">ici</a> si vous désirez vous désabonner.
							*/
						}
						?>
						<?php endif; ?>
						
						<?php echo SITE_NAME ?>&copy; <?php echo date('Y') ?> 
						</p>
					</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
</body>
</html>