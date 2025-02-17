function getSubmitAPI(config) {
    if (!config) return '';

    if (config?.id)
        return `${ApiUrl}${config.resource}/update/${config?.id}`
    else
        return `${ApiUrl}${config.resource}/new`
}