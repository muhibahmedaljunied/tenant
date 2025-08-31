<script>
    var els = 1;


    function addItem() {
        var target = document.querySelector('#accounts_table>tbody>tr');
        var options = target.querySelectorAll('select>option');
        var select = document.createElement('select');
        select.name = 'accounts[' + els + '][account]';
        select.classList.add('form-control', 'select2');
        options.forEach((option) => {
            var opt = document.createElement('option');
            opt.value = option.value;
            opt.textContent = option.textContent;
            select.append(opt);
        });
        var text = document.createElement('input');
        text.name = 'accounts[' + els + '][amount]';
        text.type = 'number';
        text.step = 'any';
        text.classList.add('form-control');
        document.querySelector('#accounts_table>tbody').append(createTr([select, text]));
        $('.select2').select2();
        els++;
    }

    function createTr(children) {
        var tr = document.createElement('tr');
        children.forEach((child) => {
            var td = document.createElement('td');
            td.append(child);
            tr.append(td);
        });
        return tr;
    }

    document.querySelector('#add_btn').addEventListener('click', addItem);
</script>