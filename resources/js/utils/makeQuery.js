export default function (params) {
    return Object.keys(params).map((key) => `${key}=${params[key]}`).join('&');
}
