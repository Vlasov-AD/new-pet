<?php
declare(strict_types=1);

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Product;
use yii\db\Query;
use yii\helpers\VarDumper;

/**
 * ProductSearch represents the model behind the search form about `common\models\Product`.
 */
class ProductSearch extends Product
{
    public int $category_id = 0;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'sort', 'created_at', 'updated_at', 'available', 'status'], 'integer'],
            [['name', 'slug', 'description'], 'safe'],
        ];
    }

    public function behaviors() {
        return [];
    }

    public function formName()
    {
        return '';
    }


    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Product::find()
            ->with(['categories'])
            ->orderBy(['sort' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => false,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'sort' => $this->sort,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        if ($this->category_id && $this->category_id !== 1) {
            $categories = (new Query())->select(['product_id'])->where(['category_id' => $this->category_id])->from('category_product')->column();
            $query->andWhere(['id' => $categories]);
        }

        $query->andFilterWhere(['like', 'name', $this->name, false])
            ->andFilterWhere(['=', 'available', $this->available])
            ->andFilterWhere(['=', 'status', $this->status]);

        return $dataProvider;
    }
}
