<?php if ($data['n_coincidencias'] > 1): ?>
	<p class="mb-0 pt-1 pb-1 text-danger">
		Esta ubicacion tiene <?php echo "(".$data['n_coincidencias'].")"; ?> coincidencias
	</p>
<?php endif ?>

<table class="table table-sm tbl tblMonit" id="<?=($data['tblId']??"")?>">
	<?php if (isset($data['bodyTbl']) && count($data['bodyTbl'])>0): ?>
		<?php if (isset($data['headTbl'])): ?>
		<thead>
			<tr>
				<?php foreach ($data['headTbl'] as $hd): ?>
					<th><?=$hd;?></th>
				<?php endforeach ?>
			</tr>
		</thead>
		<?php endif; ?>
		<tbody>
			<?php foreach ($data['bodyTbl'] as $keyRow => $rowTbl): ?>
				<tr>
					<th style="text-align: <?=$data["align"][0]??'';?>"><b><?=$keyRow;?></b></th>
					<td style="text-align: <?=$data["align"][1]??'';?>"><?=$rowTbl;?></td>
				</tr>
			<?php endforeach ?>
		</tbody>
		<tfoot>
			
		</tfoot>
	<?php else: ?>
		<tr><td style="text-align: center;">SIN REGISTROS</td></tr>
	<?php endif ?>
</table>