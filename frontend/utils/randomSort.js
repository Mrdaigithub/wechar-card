/**
 * 数组随机排序
 *
 * @param arr
 * @returns {*}
 */
export default function randomSort(arr) {
  return arr.sort((a, b) => Math.random() > 0.5 ? -1 : 1);
}
