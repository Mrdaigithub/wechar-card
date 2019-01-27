/**
 * 范围随机数
 *
 * @param min
 * @param max
 * @returns {number}
 */
export default function rangeRandom(min, max) {
  return Math.floor(Math.random() * (max - min) + min);
}
