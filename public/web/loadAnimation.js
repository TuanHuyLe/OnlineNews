/**
 * provide function show and hide loading
 * @Author LHTUAN (10/11/2020)
 * @type {{onShowLoading(): void, onHideLoading(): void}}
 */
loadingAnimation = {
    /**
     * Show loadding
     * Author: LTQUAN (06/11/2020)
     */
    onShowLoading() {
        $("#loading, #loading-display").show();
    },

    /**
     * Hide Loadding
     * Author: LTQUAN (06/11/2020)
     */
    onHideLoading() {
        $("#loading, #loading-display").hide();
    }
}
