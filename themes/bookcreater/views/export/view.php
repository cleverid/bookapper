<table>
    <?foreach($files as $file):?>
        <tr>
            <td><a href="<?=$file['url']?>"><?=$file['name']?></td>
        </tr>
    <?endforeach;?>
</table>