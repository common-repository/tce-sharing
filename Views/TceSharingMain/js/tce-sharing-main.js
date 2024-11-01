const tceRedirectToConfigurationPage = () => {
    let currentLocation = window.location.href;
    window.location.href = window.location.pathname + '?page=tce-sharing-configuration'
}
