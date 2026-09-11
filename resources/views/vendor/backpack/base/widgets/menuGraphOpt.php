<?php foreach ($data['sectMenu'] as $key => $value) : ?>
    <li class="<?= ($data['menuID'] == $value['MENUID'] ? 'lselect bg-secondary' : '') ?>">
        <label class="nav-link submenu_<?php echo $key; ?>" data-index="<?php echo $key; ?>" data-SectID="<?= $data['sectID'] ?>" data-filter="<?= $data['filter'] ?>" data-menuID="<?= $value['MENUID'] ?>" data-fathermenuid="<?= $value['MID'] ?>"><?= $value["MENUNAME"] ?></label>
    </li>
<?php endforeach ?>