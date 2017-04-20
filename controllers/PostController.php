<?php

namespace app\controllers;

use app\models\BlogUser;
use app\models\PostComment;
use Yii;
use app\models\Post;
use app\models\PostSearch;
use yii\data\Pagination;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;


/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],

            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'update', 'delete', 'index', 'view', 'comments', 'feedback', 'search-post'],
                'rules' => [
                    [
                        'actions' => ['update','delete'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function($role, $action)
                                           {
                                               if($this->isAdmin())
                                               {
                                                   return true;
                                               }

                                               if($this->isAuthor())
                                               {
                                                    return true;
                                               }

                                               return $this->redirect(['../site/error','405','You do not have permission for this action!']);
                                           }
                    ],
                    [
                        'actions' => ['create', 'comments'],
                        'allow' => true,
                        'roles' => ['user'],
                    ],
                    [
                        'actions' => ['index', 'view', 'feedback', 'search-post'],
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ],
            ],
        ];
    }

    public function isAuthor()
    {
        $userId = \Yii::$app->user->identity->id;
        $post = $this->findModel(\Yii::$app->request->get('id'));

        return $userId == $post->user_id;
    }

    public static function isAdmin()
    {
        $userId = \Yii::$app->user->identity->id;
        $role = \Yii::$app->db->createCommand("SELECT item_name FROM auth_assignment WHERE user_id=$userId")->queryScalar();

        return $role == 'admin';
    }

    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PostSearch();
        $postModel = new \app\models\Post();
        $userModel = new \app\models\BlogUser();

        $posts = $postModel::find()->orderBy(['post_id' => SORT_DESC])->all();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->pagination->pageSize=12;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'postModel' => $postModel,
            'userModel' => $userModel,
            'posts' => $posts,
        ]);
    }

    /**
     * Displays a single Post model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $postModel = Post::find()->where(['post_id' => $id])->one();

        $currUser = BlogUser::find()->where(['id' => $postModel->user_id])->one();

        $postCommentsCount = (new Query())->select('*')
                                            ->from('post_comments')
                                            ->where(['post_id' => $postModel->post_id])
                                            ->all();

        $likesCount = (new Query())->select('*')
                                    ->from('likes')
                                    ->where(['post_id' => $postModel->post_id])
                                    ->all();

        $dislikesCount = (new Query())->select('*')
                                        ->from('dislikes')
                                        ->where(['post_id' => $postModel->post_id])
                                        ->all();

        return $this->render('view', [
            'model' => $this->findModel($id),
            'author' => $currUser,
            'postCount' => $postCommentsCount,
            'likesCount' => $likesCount,
            'dislikesCount' => $dislikesCount
        ]);
    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Post();
        $id = \Yii::$app->user->identity->id;

        if ($model->load(Yii::$app->request->post())) {

            date_default_timezone_set('Europe/Berlin');

            $model->user_id = $id;
            $model->date_create = date('m/d/Y');

            if($model->save())
            {
                return $this->redirect(['view', 'id' => $model->post_id]);
            }

            throw new \yii\db\Exception("Some problem with DB connection ocurred!");

        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->post_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }



    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        \Yii::$app->db->createCommand("SET foreign_key_checks = 0;")->execute();

        $this->findModel($id)->delete();

        \Yii::$app->db->createCommand("DELETE FROM likes
                                            WHERE post_id=$id")->execute();

        \Yii::$app->db->createCommand("DELETE FROM dislikes
                                            WHERE post_id=$id")->execute();

        PostComment::deleteAll("post_id=" . $id);



        \Yii::$app->db->createCommand("SET foreign_key_checks = 1;")->execute();

        return $this->redirect(['index']);
    }

        /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
