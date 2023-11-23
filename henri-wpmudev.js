function henriWPMUDevDrawTbody() {
    const tbody = jQuery(`[name='table_product'] tbody`)
    jQuery.post(henri_wpmudev_list.url, {
        keyword: jQuery(`[name='search_product']`).val()
    }, records => {
        tbody.html(``)
        for (var record of records) {
            tbody.append(`
                <tr>
                    <td>${record.item}</td>
                    <td>${record.stock}</td>
                    <td>
                        <form method='POST'>
                            <input type='hidden' name='product_id' value='${record.id}'>
                            <input type='submit' name='edit_product' value='detail'>
                            <input type='submit' name='delete_product' value='delete'>
                        </form>
                    </td>
                </tr>
            `)
        }
    })
}

jQuery(document).ready(() => {
    if (0 < jQuery(`[name='table_product']`).length) henriWPMUDevDrawTbody()
    jQuery(`[name='search_button']`).click(e => {
        e.preventDefault()
        henriWPMUDevDrawTbody()
    })
    jQuery(`[name='reset_button']`).click(e => {
        e.preventDefault()
        jQuery(`[name='search_product']`).val(``)
        henriWPMUDevDrawTbody()
    })
})