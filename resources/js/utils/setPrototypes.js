import arrayUnique from "@/utils/prototypes/arrayUnique";

export default () => {
    if (!Array.prototype.unique) {
        Array.prototype.unique = arrayUnique();
    }
}
