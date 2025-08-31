<script>
    var els = 1;


    function addItem() {

        document.querySelector('#accounts_table>tbody').append(createTr([
            extractSelectEl('#select_location', 'location'),
            extractSelectEl('#select_product_id', 'product'),
            createElement('input', {
                'name': 'account[' + els + '][amount]',
                'type': 'number',
                'step': 'any',
                'class': 'form-control'
            }),
            createElement('input', {
                'name': 'account[' + els + '][qty]',
                'type': 'number',
                'step': 'any',
                'class': 'form-control',
            }),
        ]));
        $('.select2').select2();
        els++;
    }

    function createElement(tag, props) {
        var el = document.createElement(tag);
        Object.keys(props).forEach(function(prop) {
            if (prop === 'class') {
                el.classList.add(props[prop]);
            } else {
                el[prop] = props[prop];
            }
        });
        return el;
    }

    function extractSelectEl(selector, name) {
        var target = document.querySelector('#accounts_table>tbody>tr');
        var options = target.querySelectorAll('select' + selector + '>option');
        var select = document.createElement('select');
        select.name = 'accounts[' + els + '][' + name + ']';
        select.classList.add('form-control', 'select2');
        options.forEach((option) => {
            var opt = document.createElement('option');
            opt.value = option.value;
            opt.textContent = option.textContent;
            select.append(opt);
        });
        return select;
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
